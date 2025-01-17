<?php

namespace App\Http\Controllers;

use App\Facades\CampaignLocalization;
use App\Facades\FormCopy;
use App\Http\Resources\Attribute;
use App\Models\AttributeTemplate;
use App\Models\MiscModel;
use App\Services\FilterService;
use App\Traits\GuestAuthTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LogicException;

class CrudController extends Controller
{
    use GuestAuthTrait;

    /**
     * The view where to find the resources
     *
     * @var string
     */
    protected $view = '';

    /**
     * The name of the route for the resource
     *
     * @var string
     */
    protected $route = '';

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * Extra actions in the index view
     *
     * @var array
     */
    protected $indexActions = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var FilterService
     */
    protected $filterService;

    /**
     * If the permissions tab and pane is enabled or not.
     * @var bool
     */
    protected $tabPermissions = true;

    /**
     * If the attributes tab and pane is enabled or not.
     * @var bool
     */
    protected $tabAttributes = true;

    /**
     * If the boosted tab and pane is enabled or not.
     * @var bool
     */
    protected $tabBoosted = true;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('campaign.member');

        $this->filterService = new FilterService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->crudIndex($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function crudIndex(Request $request)
    {
        //$this->authorize('browse', $this->model);

        // Add the is_private filter only for admins.
        if (Auth::check() && Auth::user()->isAdmin()) {
            $this->filters[] = 'is_private';
        }

        $model = new $this->model;
        $this->filterService->make($this->view, request()->all(), $model);
        $name = $this->view;
        $actions = $this->indexActions;
        $filters = $this->filters;
        $filterService = $this->filterService;
        $nestedView = method_exists($this, 'tree');
        $route = $this->route;

        $base = $model
            ->preparedWith()
            ->search($this->filterService->search())
            ->order($this->filterService->order())
        ;

        // Do this to avoid an extra sql query when no filters are selected
        if ($this->filterService->hasFilters()) {
            $unfilteredCount = $base->count();
            $base = $base->filter($this->filterService->filters());

            $models = $base->paginate();
            $filteredCount =  $models->total();
        } else {
            $models = $base->paginate();
            $unfilteredCount = $filteredCount = $models->count();
        }

        return view('cruds.index', compact(
            'models',
            'name',
            'model',
            'actions',
            'filters',
            'filterService',
            'nestedView',
            'route',
            'filteredCount',
            'unfilteredCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->crudCreate();
    }
    public function crudCreate($params = [])
    {
        $this->authorize('create', $this->model);

        if (!isset($params['source'])) {
            $copyId = request()->input('copy');
            if (!empty($copyId)) {
                $model = new $this->model;
                $params['source'] = $model->findOrFail($copyId);
                FormCopy::source($params['source']);
            } else {
                $params['source'] = null;
            }
        }
        $model = new $this->model;
        $templates = $this->buildAttributeTemplates($model->entityTypeId());

        $params['ajax'] = request()->ajax();
        $params['tabPermissions'] = $this->tabPermissions && Auth::user()->can('permission', $model);
        $params['tabAttributes'] = $this->tabAttributes;
        $params['tabBoosted'] = $this->tabBoosted && CampaignLocalization::getCampaign()->boosted();
        $params['entityAttributeTemplates'] = $templates;
        $params['entityType'] = $model->getEntityType();

        return view('cruds.forms.create', array_merge(['name' => $this->view], $params));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param bool $redirectToCreated
     * @return \Illuminate\Http\RedirectResponse
     */
    public function crudStore(Request $request, $redirectToCreated = false)
    {
        $this->authorize('create', $this->model);

        // For ajax requests, send back that the validation succeeded, so we can really send the form to be saved.
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        try {
            $model = new $this->model;
            $new = $model->create($request->all());

            // Fire an event for the Entity Observer.
            $new->crudSaved();

            // MenuLink have no entity attached to them.
            if ($new->entity) {
                $new->entity->crudSaved();
            }

            $success = trans($this->view . '.create.success', [
                'name' => link_to_route(
                    $this->view . '.show',
                    e($new->name),
                    $new
                )
            ]);

            session()->flash('success_raw', $success);

            if ($request->has('submit-new')) {
                $route = route($this->route . '.create');
                return response()->redirectTo($route);
            } elseif ($request->has('submit-update')) {
                $route = route($this->route . '.edit', $new);
                return response()->redirectTo($route);
            } elseif ($request->has('submit-view')) {
                $route = route($this->route . '.show', $new);
                return response()->redirectTo($route);
            }

            if ($redirectToCreated) {
                $route = route($this->route . '.show', $new);
                return response()->redirectTo($route);
            }

            $subroute = 'index';
            if (auth()->user()->defaultNested and \Illuminate\Support\Facades\Route::has($this->route . '.tree')) {
                $subroute = 'tree';
            }
            $route = route($this->route . '.' . $subroute);
            return response()->redirectTo($route);
        } catch (LogicException $exception) {
            $error =  str_replace(' ', '_', strtolower($exception->getMessage()));
            return redirect()->back()->withInput()->with('error', trans('crud.errors.' . $error));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function crudShow(Model $model)
    {
        // Policies will always fail if they can't resolve the user.
        if (Auth::check()) {
            $this->authorize('view', $model);
        } else {
            $this->authorizeForGuest('read', $model);
        }
        $name = $this->view;
        $ajax = request()->ajax();

        // Fix for models without an entity
        if (empty($model->entity)) {
            $model->save();
            $model->load('entity');
        }

        return view(
            'cruds.show',
            compact('model', 'name', 'ajax')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MiscModel  $model
     * @return \Illuminate\Http\Response
     */
    public function crudEdit(Model $model)
    {
        $this->authorize('update', $model);

        $params = [
            'model' => $model,
            'name' => $this->view,
            'ajax' => request()->ajax(),
            'tabPermissions' => $this->tabPermissions && Auth::user()->can('permission', $model),
            'tabAttributes' => $this->tabAttributes && Auth::user()->can('attributes', $model->entity),
            'tabBoosted' => $this->tabBoosted && CampaignLocalization::getCampaign()->boosted(),
            'entityType' => $model->getEntityType()
        ];

        return view('cruds.forms.edit', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function crudUpdate(Request $request, Model $model)
    {
        $this->authorize('update', $model);

        // For ajax requests, send back that the validation succeeded, so we can really send the form to be saved.
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        try {
            $data = $this->prepareData($request, $model);
            $model->update($data);

            // Fire an event for the Entity Observer
            $model->crudSaved();

            // MenuLink have no entity attached to them.
            if ($model->entity) {
                $model->entity->crudSaved();
            }

            $success = trans($this->view . '.edit.success', [
                'name' => link_to_route(
                    $this->route . '.show',
                    e($model->name),
                    $model
                )
            ]);
            session()->flash('success_raw', $success);

            $route = route($this->route . '.show', $model->id);
            if ($request->has('submit-new')) {
                $route = route($this->route . '.create');
            } elseif ($request->has('submit-update')) {
                $route = route($this->route . '.edit', $model->id);
            } elseif ($request->has('submit-close')) {
                $subroute = 'index';
                if (auth()->user()->defaultNested and \Illuminate\Support\Facades\Route::has($this->route . '.tree')) {
                    $subroute = 'tree';
                }
                $route = route($this->route . '.' . $subroute);
            }
            return response()->redirectTo($route);
        } catch (LogicException $exception) {
            $error =  str_replace(' ', '_', strtolower($exception->getMessage()));
            return redirect()->back()->withInput()->with('error', trans('crud.errors.' . $error));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function crudDestroy(Model $model)
    {
        $this->authorize('delete', $model);

        $model->delete();

        $subroute = 'index';
        if (auth()->user()->defaultNested and \Illuminate\Support\Facades\Route::has($this->route . '.tree')) {
            $subroute = 'tree';
        }

        return redirect()->route($this->route . '.' . $subroute)
            ->with('success', trans($this->view . '.destroy.success', ['name' => $model->name]));
    }

    /**
     * Multiple delete of a model
     *
     * @param Request $request
     */
    public function deleteMany(Request $request)
    {
        $model = new $this->model;
        $ids = $request->get('model');

        $count = 0;
        foreach ($ids as $id) {
            $entity = $model->findOrFail($id);
            if ($this->authorize('delete', $entity)) {
                $entity->delete();
                $count++;
            }
        }

        $subroute = 'index';
        if (auth()->user()->defaultNested and \Illuminate\Support\Facades\Route::has($this->route . '.tree')) {
            $subroute = 'tree';
        }

        return redirect()->route($this->route . '.' . $subroute)
            ->with('success', trans_choice('crud.destroy_many.success', $count, ['count' => $count]));
    }

    /**
     * @param $model
     * @param $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function menuView($model, $view, $directView = false)
    {
        // Policies will always fail if they can't resolve the user.
        if (Auth::check()) {
            $this->authorize('view', $model);
        } else {
            $this->authorizeForGuest('read', $model);
        }
        $name = $this->view;
        $fullview = $this->view . '.' . $view;
        if ($directView) {
            $fullview = 'cruds.subpage.' . $view;
        }

        $data = [];

        if ($view == 'map-points') {
            $data = $model
                ->entity
                ->targetMapPoints()
                ->orderBy('name', 'ASC')
                ->with(['location'])
                ->has('location')
                ->paginate();
        }

        return view('cruds.subview', compact(
            'fullview',
            'model',
            'name',
            'data'
        ));
    }

    /**
     * @param Request $request
     * @param MiscModel $model
     * @return array
     */
    protected function prepareData(Request $request, MiscModel $model)
    {
        $data = $request->all();
        foreach ($model->nullableForeignKeys as $field) {
            if (!request()->has($field) && !isset($data[$field])) {
                $data[$field] = null;
            }
        }
        return $data;
    }

    /**
     * Get a list of all attribute templates available for this entity type.
     * @param $type
     * @return array
     */
    protected function buildAttributeTemplates($type): array
    {
        $attributeTemplates = [];
        $ids = [];
        $templates = AttributeTemplate::where(['entity_type_id' => $type])
            ->with(['entity', 'entity.attributes'])
            ->get();
        /** @var AttributeTemplate $attr */
        foreach ($templates as $attr) {
            $attributeTemplates[] = $attr;
            $ids[] = $attr->id;
            /** @var Attribute $child */
            foreach ($attr->ancestors()->with('entity')->get() as $child) {
                if (!in_array($child->id, $ids)) {
                    $ids[] = $child->id;
                    $attributeTemplates[] = $child;
                }
            }
        }

        return $attributeTemplates;
    }
}

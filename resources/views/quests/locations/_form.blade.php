{{ csrf_field() }}
<div class="row">
    <div class="col-md-12">
        <div class="form-group required">
            {!! Form::select2(
                'location_id',
                (isset($model) && $model->location ? $model->location : null),
                App\Models\Location::class,
                true
            ) !!}
        </div>
        <div class="form-group">
            <label>{{ trans('quests.fields.role') }}</label>
            {!! Form::text('role', null, ['placeholder' => trans('quests.placeholders.role'), 'class' => 'form-control', 'maxlength' => 45]) !!}
        </div>
        <div class="form-group">
            <label>{{ trans('quests.locations.fields.description') }}</label>
            {!! Form::textarea('description', null, ['class' => 'form-control html-editor', 'id' => 'description']) !!}
        </div>

        @if (Auth::user()->isAdmin())
        <div class="form-group">
            {!! Form::hidden('is_private', 0) !!}
            <label>{!! Form::checkbox('is_private') !!}
                {{ trans('crud.fields.is_private') }}
            </label>
            <p class="help-block">{{ trans('crud.hints.is_private') }}</p>
        </div>
        @endif
    </div>
</div>

{!! Form::hidden('quest_id', $parent->id) !!}
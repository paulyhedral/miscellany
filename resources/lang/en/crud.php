<?php

return [
    'actions'           => [
        'apply'             => 'Apply',
        'back'              => 'Back',
        'copy'              => 'Copy',
        'copy_to_campaign'  => 'Copy to Campaign',
        'explore_view'      => 'Nested View',
        'export'            => 'Export',
        'find_out_more'     => 'Find out more',
        'go_to'             => 'Go to :name',
        'more'              => 'More Actions',
        'move'              => 'Move',
        'new'               => 'New',
        'next'              => 'Next',
        'private'           => 'Private',
        'public'            => 'Public',
    ],
    'add'               => 'Add',
    'attributes'        => [
        'actions'       => [
            'add'               => 'Add an attribute',
            'add_block'         => 'Add a block',
            'add_checkbox'      => 'Add a checkbox',
            'add_text'          => 'Add a text',
            'apply_template'    => 'Apply an Attribute Template',
            'manage'            => 'Manage',
            'remove_all'        => 'Delete All',
        ],
        'create'        => [
            'description'   => 'Create a new attribute',
            'success'       => 'Attribute :name added to :entity.',
            'title'         => 'New Attribute for :name',
        ],
        'destroy'       => [
            'success'   => 'Attribute :name for :entity removed.',
        ],
        'edit'          => [
            'description'   => 'Update an existing attribute',
            'success'       => 'Attribute :name for :entity updated.',
            'title'         => 'Update attribute for :name',
        ],
        'fields'        => [
            'attribute'             => 'Attribute',
            'community_templates'   => 'Community Templates',
            'is_private'            => 'Private Attributes',
            'is_star'               => 'Pinned',
            'template'              => 'Template',
            'value'                 => 'Value',
        ],
        'helpers'       => [
            'delete_all'    => 'Are you sure you want to delete all of this entity\'s attributes?',
        ],
        'hints'         => [
            'is_private'    => 'You can hide all the attributes of an entity for all members outside of the admin role by making it private.',
        ],
        'index'         => [
            'success'   => 'Attributes for :entity updated.',
            'title'     => 'Attributes for :name',
        ],
        'placeholders'  => [
            'attribute' => 'Number of conquests, Challenge Rating, Initiative, Population',
            'block'     => 'Block name',
            'checkbox'  => 'Checkbox name',
            'section'   => 'Section name',
            'template'  => 'Select a template',
            'value'     => 'Value of the attribute',
        ],
        'template'      => [
            'success'   => 'Attribute Template :name applied to :entity',
            'title'     => 'Apply an Attribute Template for :name',
        ],
        'types'         => [
            'attribute' => 'Attribute',
            'block'     => 'Block',
            'checkbox'  => 'Checkbox',
            'section'   => 'Section',
            'text'      => 'Multiline Text',
        ],
        'visibility'    => [
            'entry'     => 'Attribute is displayed on the entity menu.',
            'private'   => 'Attribute only visible to members of the "Admin" role.',
            'public'    => 'Attribute visible to all members.',
            'tab'       => 'Attribute is displayed only on the Attributes tab.',
        ],
    ],
    'boosted' => 'Boosted',
    'bulk'              => [
        'errors'        => [
            'admin' => 'Only campaign admins can change the private status of entities.',
        ],
        'permissions'   => [
            'fields'    => [
                'override'  => 'Override',
            ],
            'helpers'   => [
                'override'  => 'If selected, permissions of the selected entities will be overwritten with these. If unchecked, the selected permissions will be added to the existing ones.',
            ],
            'title'     => 'Change permissions for several entities',
        ],
        'success'       => [
            'permissions'   => 'Permissions changed for :count entity.|Permissions changed for :count entities.',
            'private'       => ':count entity is now private|:count entities are now private.',
            'public'        => ':count entity is now visible|:count entities are now visible.',
        ],
    ],
    'cancel'            => 'Cancel',
    'click_modal'       => [
        'close'     => 'Close',
        'confirm'   => 'Confirm',
        'title'     => 'Confirm your action',
    ],
    'copy_to_campaign'  => [
        'panel' => 'Copy',
        'title' => 'Copy \':name\' to another campaign',
    ],
    'create'            => 'Create',
    'datagrid'          => [
        'empty' => 'Nothing to show yet.',
    ],
    'delete_modal'      => [
        'close'         => 'Close',
        'delete'        => 'Delete',
        'description'   => 'Are you sure you want to remove :tag?',
        'mirrored'      => 'Remove mirrored relation.',
        'title'         => 'Delete confirmation',
    ],
    'destroy_many'      => [
        'success'   => 'Deleted :count entity|Deleted :count entities.',
    ],
    'edit'              => 'Edit',
    'errors'            => [
        'node_must_not_be_a_descendant' => 'Invalid node (tag, parent location): it would be a descendant of itself.',
    ],
    'events'            => [
        'hint'  => 'Shown below is a list of all the Calendars in which this entity was added using the "Add an event to a calendar" interface.',
    ],
    'export'            => 'Export',
    'fields'            => [
        'attribute_template'    => 'Attribute Template',
        'calendar'              => 'Calendar',
        'calendar_date'         => 'Calendar Date',
        'character'             => 'Character',
        'copy_attributes'       => 'Copy Attributes',
        'copy_notes'            => 'Copy Entity Notes',
        'creator'               => 'Creator',
        'dice_roll'             => 'Dice Roll',
        'entity'                => 'Entity',
        'entity_type'           => 'Entity Type',
        'entry'                 => 'Entry',
        'event'                 => 'Event',
        'excerpt'               => 'Excerpt',
        'family'                => 'Family',
        'files'                 => 'Files',
        'header_image'          => 'Header Image',
        'image'                 => 'Image',
        'is_private'            => 'Private',
        'is_star'               => 'Pinned',
        'item'                  => 'Item',
        'location'              => 'Location',
        'name'                  => 'Name',
        'organisation'          => 'Organisation',
        'race'                  => 'Race',
        'tag'                   => 'Tag',
        'tags'                  => 'Tags',
        'tooltip'               => 'Tooltip',
        'visibility'            => 'Visibility',
    ],
    'files'             => [
        'actions'   => [
            'drop'      => 'Click to Add or Drop a file',
            'manage'    => 'Manage Entity Files',
        ],
        'errors'    => [
            'max'   => 'You have reached the maximum number (:max) of files for this entity.',
        ],
        'files'     => 'Uploaded Files',
        'hints'     => [
            'limit'         => 'Each entity can have a maximum of :max files uploaded to it.',
            'limitations'   => 'Supported formats: jpg, png, gif, and pdf. Max file size: :size',
        ],
        'title'     => 'Entity Files for :name',
    ],
    'filter'            => 'Filter',
    'filters'           => [
        'all'       => 'Filter to all descendants',
        'clear'     => 'Clear Filters',
        'direct'    => 'Filter to direct descendants',
        'filtered'  => 'Showing :count of :total :entity.',
        'hide'      => 'Hide Filters',
        'show'      => 'Show Filters',
        'title'     => 'Filters',
    ],
    'forms'             => [
        'actions'       => [
            'calendar'  => 'Add a calendar date',
        ],
        'copy_options'  => 'Copy Options',
    ],
    'hidden'            => 'Hidden',
    'hints'             => [
        'attribute_template'    => 'Apply an attribute template directly when creating this entity.',
        'calendar_date'         => 'A calendar date allows easy filtering in lists, and also maintains a calendar event in the selected calendar.',
        'header_image'          => 'This image is placed above the entity. For best results, use a wide image.',
        'image_limitations'     => 'Supported formats: jpg, png and gif. Max file size: :size.',
        'image_patreon'         => 'Increase file size limit?',
        'is_private'            => 'If set to private, this entity will only be visible to members who are in the campaign\'s "Admin" role.',
        'is_star'               => 'Pinned elements will appear on the entity\'s menu',
        'map_limitations'       => 'Supported formats: jpg, png, gif and svg. Max file size: :size.',
        'tooltip'               => 'Replace the automatically generated tooltip with the following contents.',
        'visibility'            => 'Setting the visibility to admin means only members in the Admin campaign role can view this. Setting it to self means only you can view this.',
    ],
    'history'           => [
        'created'   => 'Created by <strong>:name</strong> <span data-toggle="tooltip" title=":realdate">:date</span>',
        'unknown'   => 'Unknown',
        'updated'   => 'Last modified by <strong>:name</strong> <span data-toggle="tooltip" title=":realdate">:date</span>',
        'view'      => 'View entity log',
    ],
    'image'             => [
        'error' => 'We weren\'t able to get the image you requested. It could be that the website doesn\'t allow us to download the image (typically for Squarespace and DeviantArt), or that the link is no longer valid. Please also make sure that the image isn\'t larger than :size.',
    ],
    'is_private'        => 'This entity is private and not visible by non-admin users.',
    'linking_help'      => 'How can I link to other entries?',
    'manage'            => 'Manage',
    'move'              => [
        'description'   => 'Move this entity to another place',
        'errors'        => [
            'permission'        => 'You aren\'t allowed to create entities of that type in the target campaign.',
            'same_campaign'     => 'You need to select another campaign to move the entity to.',
            'unknown_campaign'  => 'Unknown campaign.',
        ],
        'fields'        => [
            'campaign'  => 'New campaign',
            'copy'      => 'Make a copy',
            'target'    => 'New type',
        ],
        'hints'         => [
            'campaign'  => 'You can also try to move this entity to another campaign.',
            'copy'      => 'Select this option if you want to create copy in the new campaign.',
            'target'    => 'Please be aware that some data might be lost when moving an element from one type to another.',
        ],
        'success'       => 'Entity \':name\' moved.',
        'success_copy'  => 'Entity \':name\' copied.',
        'title'         => 'Move :name',
    ],
    'new_entity'        => [
        'error' => 'Please review your values.',
        'fields'=> [
            'name'  => 'Name',
        ],
        'title' => 'New entity',
    ],
    'or_cancel'         => 'or <a href=":url">cancel</a>',
    'panels'            => [
        'appearance'            => 'Appearance',
        'attribute_template'    => 'Attribute Template',
        'calendar_date'         => 'Calendar Date',
        'entry'                 => 'Entry',
        'general_information'   => 'General Information',
        'move'                  => 'Move',
        'system'                => 'System',
    ],
    'permissions'       => [
        'action'    => 'Action',
        'actions'   => [
            'bulk'          => [
                'add'       => 'Add',
                'remove'    => 'Remove',
            ],
            'delete'        => 'Delete',
            'edit'          => 'Edit',
            'entity_note'   => 'Entity Notes',
            'read'          => 'Read',
        ],
        'allowed'   => 'Allowed',
        'fields'    => [
            'member'    => 'Member',
            'role'      => 'Role',
        ],
        'helper'    => 'Use this interface to fine-tune which users and roles that can interact with this entity.',
        'inherited' => 'This role already has this permission set for this entity type.',
        'inherited_by' => 'This user is part of the \':role\' role which grants this permissions on this entity type.',
        'success'   => 'Permissions saved.',
        'title'     => 'Permissions',
    ],
    'placeholders'      => [
        'calendar'      => 'Choose a calendar',
        'character'     => 'Choose a character',
        'entity'        => 'Entity',
        'event'         => 'Choose an event',
        'family'        => 'Choose a family',
        'image_url'     => 'You can upload an image from a URL instead',
        'item'          => 'Choose an item',
        'location'      => 'Choose a location',
        'organisation'  => 'Choose an organisation',
        'race'          => 'Choose a race',
        'tag'           => 'Choose a tag',
    ],
    'relations'         => [
        'actions'   => [
            'add'   => 'Add a relation',
        ],
        'fields'    => [
            'location'  => 'Location',
            'name'      => 'Name',
            'relation'  => 'Relation',
        ],
        'hint'      => 'Relations between entities can be set up to represent their connections.',
    ],
    'remove'            => 'Remove',
    'rename'            => 'Rename',
    'save'              => 'Save',
    'save_and_close'    => 'Save and Close',
    'save_and_new'      => 'Save and New',
    'save_and_update'   => 'Save and Update',
    'save_and_view'     => 'Save and View',
    'search'            => 'Search',
    'select'            => 'Select',
    'tabs'              => [
        'attributes'    => 'Attributes',
        'boost'         => 'Boost',
        'calendars'     => 'Calendars',
        'default'       => 'Default',
        'events'        => 'Events',
        'inventory'     => 'Inventory',
        'map-points'    => 'Map Points',
        'mentions'      => 'Mentions',
        'menu'          => 'Menu',
        'notes'         => 'Entity Notes',
        'permissions'   => 'Permissions',
        'relations'     => 'Relations',
        'tooltip'       => 'Tooltip',
    ],
    'update'            => 'Update',
    'users'             => [
        'unknown'   => 'Unknown',
    ],
    'view'              => 'View',
    'visibilities'      => [
        'admin' => 'Admin',
        'all'   => 'All',
        'self'  => 'Self',
    ],
];

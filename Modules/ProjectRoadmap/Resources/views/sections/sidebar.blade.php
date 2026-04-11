@if (in_array('projectroadmap', user_modules()) && user()->permission('view_roadmap') != 'none')
    <x-menu-item :link="route('roadmap.index')"
                 :text="__('projectroadmap::app.menu.roadmap')"
                 icon="fa-map"
    />
@endif

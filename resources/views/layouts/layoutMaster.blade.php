@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();
@endphp

@isset($configData["layout"])
@include((( $configData["layout"] === 'horizontal') ? 'layouts.horizontalLayout' :
(( $configData["layout"] === 'blank') ? 'layouts.blankLayout' :
(($configData["layout"] === 'front') ? 'layouts.layoutFront' : 'layouts.contentNavbarLayout') )))
@endisset
@if ($errors->any())
    @foreach ($errors->all() as $error)
      @php
        flash()->addError($error);
      @endphp
    @endforeach
@endif
@if (session('success'))
<div class="alert alert-success">
    {{ flash('success') }}
</div>
@endif

<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>
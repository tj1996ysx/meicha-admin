{{-- Show the errors, if any --}}
@if ($crud->groupedErrorsEnabled() && $errors->any())
    <div class="alert alert-danger" role="alert">
      <div class="alert-text">
        <h4 class="alert-heading">{{ trans('backpack::crud.please_fix') }}</h4>
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>

@endif



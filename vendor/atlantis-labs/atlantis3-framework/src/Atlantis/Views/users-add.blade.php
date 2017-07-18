@extends('atlantis-admin::admin-shell')

@section('title')
Add User | A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('content')
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Add User</h1>
        @if (isset($msgInfo))
        <div class="callout warning">
          <h5>{!! $msgInfo !!}</h5>
        </div>
        @endif
        @if (isset($msgSuccess))
        <div class="callout success">
          <h5>{!! $msgSuccess !!}</h5>
        </div>
        @endif
        @if (isset($msgError))
        <div class="callout alert">
          <h5>{!! $msgError !!}</h5>
        </div>
        @endif
      </div>
    </div>
  </section>
  <section class="editscreen">
    {!! Form::open(['url' => 'admin/users/add', 'data-abide' => '', 'novalidate'=> '']) !!}
    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
            <a href="/admin/users" class="back button tiny top primary" title="Go to Users" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            {!! Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
            {!! Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">New User</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">

            <div class="row">
              <div class="columns large-12">
                <div class="row">
                  <div class="columns medium-4">
                    @if ($errors->get('name'))
                    <label for="name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                      {!! Form::input('text', 'name', old('name'), ['class' => 'is-invalid-input', 'id'=>'name']) !!}
                    </label>
                    @else
                    <label for="name">Username <span class="form-error">is required.</span>
                      {!! Form::input('text', 'name', old('name'), ['id'=>'name', 'required'=>'required']) !!}
                    </label>
                    @endif

                    @if ($errors->get('email'))
                    <label for="email" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('email')[0] }}</span>
                      {!! Form::input('text', 'email', old('email'), ['class' => 'is-invalid-input', 'id'=>'email']) !!}
                    </label>
                    @else
                    <label for="email">E-mail <span class="form-error">is required.</span>
                      {!! Form::input('text', 'email', old('email'), ['id'=>'email', 'required'=>'required']) !!}
                    </label>
                    @endif

                    @if ($errors->get('password'))
                    <label for="password" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('password')[0] }}</span>
                      {!! Form::input('password', 'password', old('password'), ['class' => 'is-invalid-input', 'id'=>'password']) !!}
                    </label>
                    @else
                    <label for="password">Password <span class="form-error">is required.</span>
                      {!! Form::input('password', 'password', old('password'), ['id'=>'password', 'required'=>'required']) !!}
                    </label>
                    @endif

                    @if ($errors->get('password_confirm'))
                    <label for="password_confirm" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('password_confirm')[0] }}</span>
                      {!! Form::input('password', 'password_confirm', old('password_confirm'), ['class' => 'is-invalid-input', 'id'=>'password_confirm']) !!}
                    </label>
                    @else
                    <label for="password_confirm">Confirm New Password <span class="form-error">is required.</span>
                      {!! Form::input('password', 'password_confirm', old('password_confirm'), ['id'=>'password_confirm', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-4">
                    <label for="language">Language
                      {!! Form::select('language', $aLang, 'en', ['id' => 'language']) !!} 
                    </label>                  
                    <label for="editor">Editor
                      {!! Form::select('editor', $aEditors, NULL, ['id' => 'editor']) !!} 
                    </label>
                  </div>
                  <div class="columns medium-4 end">
                    @foreach ($oRoles as $role)
                    @if ($role->id == 1 || $role->id == 2)
                    <div class="switch tiny">
                      <label for="role_{!! $role->id !!}">{{ $role->name }}</label>
                      {!! Form::checkbox('roles[]', $role->id, FALSE, ['class' => 'switch-input', 'id' => 'role_' . $role->id]) !!}
                      <label class="switch-paddle" for="role_{!! $role->id !!}"></label>
                      <i>{{ $role->description }}</i>
                    </div>
                    @endif
                    @endforeach
                    <hr>
                    @foreach ($oRoles as $role)
                    @if (!($role->id == 1 || $role->id == 2))
                    <div class="switch tiny">
                      <label for="role_{!! $role->id !!}">{{ $role->name }}</label>
                      {!! Form::checkbox('roles[]', $role->id, FALSE, ['class' => 'switch-input', 'id' => 'role_' . $role->id]) !!}
                      <label class="switch-paddle" for="role_{!! $role->id !!}"></label>
                      <i>{{ $role->description }}</i>
                    </div>
                    @endif
                    @endforeach
                  </div>
                </div>
              </div>            
            </div>
          </div>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
  </section>
</main>
<footer>
  
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop
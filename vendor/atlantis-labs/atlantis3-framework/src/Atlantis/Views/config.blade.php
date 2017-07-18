@extends('atlantis-admin::admin-shell')

@section('title')
Config | A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
{{-- <script src="http://a3.angel.dev.gentecsys.net/media/js/vendor/jquery.js"></script> --}}
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
                <h1 class="huge page-title">System Config</h1>
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
        {!! Form::open(['url' => 'admin/config/update', 'data-abide' => '', 'novalidate'=> '']) !!}
        <div class="row">
            <div class="columns">
                <div class="float-right">
                    <div class="buttons">
                        <input type="submit" name="_update" value="Update" id="update-btn" class="alert button">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12">
                <ul class="tabs" data-tabs id="example-tabs">
                    <li class="tabs-title is-active">
                        <a href="#panel1" aria-selected="true">
                            System Configuration - Framework Version: <?=$framework_version;?>
                        </a>
                    </li>
                </ul>
                <div class="tabs-content" data-tabs-content="example-tabs">
                    <div class="tabs-panel is-active" id="panel1">
                        <div class="row">
                            <div class="columns large-9">
                                <div class="row">
                                    <div class="columns medium-6">
                                        <label for="site_name"> Site Name
                                            <span class="form-error">is required.</span>
                                            <span class="icon icon-Help top" data-tooltip title="Application / SiteName"></span>
                                            {!! Form::input('text', 'site_name', old('site_name', $site_name), ['id'=>'site_name', 'required'=>'required']) !!}
                                        </label>
                                        <div class="switch tiny">
                                            <label for="include_title">Include site name in title field
                                                <span class="icon icon-Help top" data-tooltip title="Enabling this will include the site name title field"></span>
                                            </label>
                                            {!! Form::checkbox('include_title', TRUE, $include_title, ['class' => 'switch-input', 'id' => 'include_title']) !!}
                                            <label class="switch-paddle" for="include_title">
                                            </label>
                                        </div>
                                        <label for="domain_name">Domain Name
                                            <span class="icon icon-Help top" data-tooltip title="Domain name"></span>
                                            {!! Form::input('text', 'domain_name', old('domain_name', $domain_name), ['id'=>'domain_name']) !!}
                                        </label>
                                        <label for="meta_keywords">Default Keywords
                                            <small id="meta_keywords_info">255 characters left</small>                      
                                            {!! Form::input('text', 'default_meta_keywords', old('default_meta_keywords', $default_meta_keywords), ['id'=>'meta_keywords']) !!}
                                        </label>                    
                                        <label for="meta_description">Default Description
                                            <span class="form-error">is required.</span>
                                            <small id="meta_description_info">255 characters left</small>                     
                                            {!! Form::input('text', 'default_meta_description', old('default_meta_description', $default_meta_description), ['id'=>'meta_description', 'required'=>'required']) !!}
                                        </label>
                                        <label for="frontend_shell_view"> The Front end Shell View
                                            <span class="icon icon-Help top" data-tooltip title="This view is the wrapper for all the other views it holds the common elements like head , body , etc /modules/atlantis/views/shell.php.">
                                            </span>
                                            {!! Form::input('text', 'frontend_shell_view', old('frontend_shell_view', $frontend_shell_view), ['id'=>'frontend_shell_view']) !!}
                                        </label>
                                        <label for="admin_items_per_page">Items per page on the admin screens
                                            {!! Form::input('text', 'admin_items_per_page', old('admin_items_per_page', $admin_items_per_page), ['id'=>'admin_items_per_page']) !!}
                                        </label>
                                        <label for="default_language">Default Language
                                            <span class="icon icon-Help top" data-tooltip title="Front-end default language"></span>
                                            {!! Form::select('default_language', $aLang, $default_language, ['id' => 'default_language']) !!} 
                                        </label>
                                        <label for="cache_lifetime">Cache lifetime (seconds)
                                            {!! Form::input('text', 'cache_lifetime', old('cache_lifetime', $cache_lifetime), ['id'=>'cache_lifetime']) !!}
                                        </label>
                                        <div class="switch tiny">
                                            <label for="show_shortcut_bar">Show shortcut bar</label>
                                            {!! Form::checkbox('show_shortcut_bar', TRUE, $show_shortcut_bar, ['class' => 'switch-input', 'id' => 'show_shortcut_bar']) !!}
                                            <label class="switch-paddle" for="show_shortcut_bar">
                                            </label>
                                        </div>
                                        <div class="switch tiny">
                                            <label for="cache">Enable cache</label>
                                            {!! Form::checkbox('cache', TRUE, $cache, ['class' => 'switch-input', 'id' => 'cache']) !!}
                                            <label class="switch-paddle" for="cache">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="columns medium-6">
                                        <label for="allowed_max_filesize">Allowed max file size for upload in MB
                                            {!! Form::input('text', 'allowed_max_filesize', old('allowed_max_filesize', $allowed_max_filesize), ['id'=>'allowed_max_filesize']) !!}
                                        </label>
                                        <label for="user_media_upload">Media upload directory
                                            {!! Form::input('text', 'user_media_upload', old('user_media_upload', $user_media_upload), ['id'=>'user_media_upload']) !!}
                                        </label>

                                        <label for="allowed_image_extensions">Allowed image extensions
                                            <span class="icon icon-Help top" data-tooltip title="For example: png, jpeg"></span>
                                            {!! Form::input('text', 'allowed_image_extensions', old('allowed_image_extensions', $allowed_image_extensions), ['id'=>'allowed_image_extensions']) !!}
                                        </label>
                                        <label for="allowed_others_extensions">Allowed others extensions
                                            <span class="icon icon-Help top" data-tooltip title="For example: pdf, txt"></span>
                                            {!! Form::input('text', 'allowed_others_extensions', old('allowed_others_extensions', $allowed_others_extensions), ['id'=>'allowed_others_extensions']) !!}
                                        </label>
                                        <label for="static_images">Static Images
                                            <span class="icon icon-Help top" data-tooltip title="Resize name/fullsize/thumbnail"></span>
                                            {!! Form::textarea('static_images', old('static_images', $static_images), ['rows' => 4, 'cols' => '30', 'id' => 'static_images']) !!}
                                        </label>
                                        <label for="responsive_images">Responsive Images
                                            <span class="icon icon-Help top" data-tooltip title="Resize name/desktop/tablet/phone/thumbnail"></span>
                                            {!! Form::textarea('responsive_images', old('responsive_images', $responsive_images), ['rows' => 4, 'cols' => '30', 'id' => 'responsive_images']) !!}
                                        </label>  
                                        <label for="responsive_images">Responsive Breakpoint sizes
                                            <span class="icon icon-Help top" data-tooltip title='large/medium - Breakpoint at which responsive images switch from small to medium size. Breakpoint at which responsive images switch from medium to large size.'></span>
                                            {!! Form::textarea('responsive_breakpoints', old('responsive_breakpoints', $responsive_breakpoints), ['rows' => 4, 'cols' => '30', 'id' => 'responsive_breakpoints']) !!}
                                        </label> 
                                    </div>
                                </div>
                            </div>
                            <div class="columns large-3">
                                <ul class="accordion" data-accordion>
                                    <li class="accordion-item is-active" data-accordion-item>
                                        <a href="#" class="accordion-title">Default Styles</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::textarea('default_styles', old('default_styles', $default_styles), ['rows' => 4, 'cols' => '30', 'id' => 'default_styles']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">Default Scripts</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::textarea('default_scripts', old('default_scripts', $default_scripts), ['rows' => 4, 'cols' => '30', 'id' => 'default_scripts']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">Excluded Scripts</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::textarea('excluded_scripts', old('excluded_scripts', $excluded_scripts), ['rows' => 4, 'cols' => '30', 'id' => 'excluded_scripts']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">Default 404 view</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::input('text', 'default_404_view', old('default_404_view', $default_404_view), ['id'=>'default_404_view']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">Amazon S3 and CloudFront</a>
                                        <div class="accordion-content" data-tab-content>
                                            <p>S3 bucket url</p>
                                            {!! Form::input('text', 'amazon_s3_url', old('amazon_s3_url', $amazon_s3_url), ['id'=>'cdn']) !!}
                                            <p>CloudFront url</p>
                                            {!! Form::input('text', 'amazon_cloudfront_url', old('amazon_cloudfront_url', $amazon_cloudfront_url), ['id'=>'cdn']) !!}
                                            <p>Use S3 for file storage</p>
                                            <div class="switch tiny">
                                                {!! Form::checkbox('use_amazon_s3', 1, $use_amazon_s3, ['class' => 'switch-input', 'id' => 's3Switch']) !!}
                                                <label class="switch-paddle" for="s3Switch">
                                                </label>
                                            </div>
                                            <p>Use CloudFront as CDN</p>
                                            <div class="switch tiny">
                                                {!! Form::checkbox('use_amazon_cdn', 1, $use_amazon_cdn, ['class' => 'switch-input', 'id' => 's3CDNSwitch']) !!}
                                                <label class="switch-paddle" for="s3CDNSwitch">
                                                </label>
                                            </div>
                                            <p>Delete local file after upload</p>
                                            <div class="switch tiny">
                                                {!! Form::checkbox('delete_local_file', 1, $delete_local_file, ['class' => 'switch-input', 'id' => 's3DeleteSwitch']) !!}
                                                <label class="switch-paddle" for="s3DeleteSwitch">
                                                </label>
                                            </div>
                                            <a data-open="syncFiles" class="alert button">Sync Files</a>
                                            <a data-open="invalidateFiles" class="alert button">Invalidate Files</a>
                                        </div>
                                    </li>
                                </ul>
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
    {!! \Atlantis\Helpers\Modal::syncFiles('syncFiles') !!}
    {!! \Atlantis\Helpers\Modal::invalidateFiles('invalidateFiles') !!}
</footer>
@stop
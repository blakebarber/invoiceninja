@extends('header')

@section('content') 
    @parent
    @include('accounts.nav', ['selected' => ACCOUNT_EMAIL_SETTINGS, 'advanced' => true])

    {!! Former::open()->addClass('warn-on-exit') !!}
    {{ Former::populate($account) }}
    {{ Former::populateField('pdf_email_attachment', intval($account->pdf_email_attachment)) }}
    {{ Former::populateField('enable_email_markup', intval($account->enable_email_markup)) }}

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{!! trans('texts.email_settings') !!}</h3>
        </div>
        <div class="panel-body form-padding-right">
            {!! Former::checkbox('pdf_email_attachment')->text(trans('texts.enable')) !!}
            &nbsp;
            {{-- Former::select('recurring_hour')->options($recurringHours) --}}

            {!! Former::inline_radios('custom_invoice_link')
                    ->onchange('onCustomLinkChange()')
                    ->label(trans('texts.invoice_link'))
                    ->radios([
                        trans('texts.subdomain') => ['value' => 'subdomain', 'name' => 'custom_link'],
                        trans('texts.website') => ['value' => 'website', 'name' => 'custom_link'],
                    ])->check($account->iframe_url ? 'website' : 'subdomain') !!}
            {{ Former::setOption('capitalize_translations', false) }}

            {!! Former::text('subdomain')
                        ->placeholder(trans('texts.www'))
                        ->onchange('onSubdomainChange()')
                        ->addGroupClass('subdomain')
                        ->label(' ')
                        ->help(trans('texts.subdomain_help')) !!}

            {!! Former::text('iframe_url')
                        ->placeholder('http://www.example.com/invoice')
                        ->appendIcon('question-sign')
                        ->addGroupClass('iframe_url')
                        ->label(' ')
                        ->help(trans('texts.subdomain_help')) !!}
            &nbsp;
            {!! Former::select('email_design_id')
                        ->style('width: 200px')
                        ->addOption(trans('texts.plain'), 1)
                        ->addOption(trans('texts.light'), 2)
                        ->addOption(trans('texts.dark'), 3)
                        ->help(trans('texts.email_design_help')) !!}
            &nbsp;
            @if (Utils::isNinja())
                {!! Former::checkbox('enable_email_markup')
                        ->text(trans('texts.enable') . 
                            '<a href="'.EMAIL_MARKUP_URL.'" target="_blank" title="'.trans('texts.learn_more').'">' . Icon::create('question-sign') . '</a> ')
                        ->help(trans('texts.enable_email_markup_help')) !!}
            @endif
        </div>
    </div>

    @if (Auth::user()->isPro())
        <center>
            {!! Button::success(trans('texts.save'))->large()->submit()->appendIcon(Icon::create('floppy-disk')) !!}
        </center>
    @endif

    <div class="modal fade" id="iframeHelpModal" tabindex="-1" role="dialog" aria-labelledby="iframeHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="min-width:150px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="iframeHelpModalLabel">{{ trans('texts.iframe_url') }}</h4>
                </div>

                <div class="modal-body">
                    <p>{{ trans('texts.iframe_url_help1') }}</p>
                    <pre>&lt;center&gt;
    &lt;iframe id="invoiceIFrame" width="1000" height="1200"&gt;&lt;/iframe&gt;
&lt;center&gt;
&lt;script language="javascript"&gt;
    var iframe = document.getElementById('invoiceIFrame');
    iframe.src = '{{ SITE_URL }}/view/' 
                 + window.location.search.substring(1);
&lt;/script&gt;</pre>
                    <p>{{ trans('texts.iframe_url_help2') }}</p>
                    </div>

                <div class="modal-footer" style="margin-top: 0px">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{ trans('texts.close') }}</button>
                </div>

            </div>
        </div>
    </div>

    {!! Former::close() !!}

    <script type="text/javascript">

    function onSubdomainChange() {
        var input = $('#subdomain');
        var val = input.val();
        if (!val) return;
        val = val.replace(/[^a-zA-Z0-9_\-]/g, '').toLowerCase().substring(0, {{ MAX_SUBDOMAIN_LENGTH }});
        input.val(val);
    }

    function onCustomLinkChange() {
        var val = $('input[name=custom_link]:checked').val()
        if (val == 'subdomain') {
            $('.subdomain').show();
            $('.iframe_url').hide();
        } else {
            $('.subdomain').hide();
            $('.iframe_url').show();
        }
    }

    $('.iframe_url .input-group-addon').click(function() {
        $('#iframeHelpModal').modal('show');
    });


    $(function() {          
        onCustomLinkChange();

        $('#subdomain').change(function() {
            $('#iframe_url').val('');
        });
        $('#iframe_url').change(function() {
            $('#subdomain').val('');
        });
    });

    </script>
@stop
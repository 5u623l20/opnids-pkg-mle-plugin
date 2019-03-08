{% set theme_name = ui_theme|default('opnids') %}

<div>
    <div id="mle-status"></div>

    <pre id="editor" style="height: 650px;"></pre>

    <div>
        <button class="btn btn-primary" id="save-mle-config" type="button"><b>Apply</b></button>
    </div>
</div>

<script src="/ui/themes/{{ theme_name }}/build/js/ace.js"></script>
<script src="/ui/themes/{{ theme_name }}/build/js/mode-lua.js"></script>
<script src="/ui/themes/{{ theme_name }}/build/js/theme-github.js"></script>

<script>
    const form = { frm_mle: '/api/mle/settings/get' };
    var html =
        '<div class="alert alert-warning" role="alert"><span id="mle-message">Please wait...</span><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" >&times;</span></button ></div >';

    mapDataToFormUI(form).done(function(data) {
        // start an instance of the editor
        const editor = ace.edit('editor');
        editor.session.setMode('ace/mode/lua');
        editor.session.setUseWrapMode(true);
        editor.session.setValue(window.atob(data.frm_mle.mle.config));

        $('#save-mle-config').click(function(event) {
            event.preventDefault();
            event.stopPropagation();
            $('#mle-status').html(html);

            sendData = { ...data.frm_mle };
            sendData.mle.config = window.btoa(editor.getValue());

            saveObjectToServer('/api/mle/settings/set', sendData).then(function(resp) {
                ajaxCall('/api/mle/service/reload', {}, function(data, status) {
                    if (data.status === 'ok') {
                        $('#mle-message').text('Config saved.');
                    }
                });
            });
        });
    });
</script>
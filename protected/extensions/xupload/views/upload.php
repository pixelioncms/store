<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td style="width:10%">
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td style="width:30%">
            <div class="progress progress-striped active contentProgress " role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="progress-bar progress-bar-success barG" style="width:0%;"></div>
            </div>
        </td>
        <td style="width:20%">
            {% if (!i && !o.options.autoUpload) { %}
            <button class="btn btn-primary start" disabled>
                <i class="glyphicon glyphicon-upload"></i>
                <span>Start</span>
            </button>
            {% } %}
            {% if (!i) { %}
                {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                        <i class="icon-trashcan"></i>
                    <span><?=Yii::t('app','DELETE')?></span>
                </button>
                <!--<input type="checkbox" name="delete" value="1" class="toggle">-->
                {% } else { %}
                <button class="btn btn-default cancel">
                    <i class="icon-cancel-circle"></i>
                    <span><?=Yii::t('app','CANCEL')?></span>
                </button>
                {% } %}
            {% } %}
        </td>
    </tr>
    {% } %}
</script>

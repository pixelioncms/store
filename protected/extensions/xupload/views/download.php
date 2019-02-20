<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade {% if (file.useCover && file.cover == 1) { %}active{% } %}">
        <td  style="width:10%">
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
            <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td style="width:30%">
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td style="width:20%">
            <div class="btn-group">
            {% if (file.deleteUrl) { %}
            <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="icon-delete"></i>
                <span><?=Yii::t('app','DELETE')?></span>
            </button>
            <!--<input type="checkbox" name="delete" value="1" class="toggle">-->
            {% } else { %}
            <button class="btn btn-warning cancel">
                <i class="icon-cancel24"></i>
                <span><?=Yii::t('app','CANCEL')?></span>
            </button>
            {% } %}
            {% if (file.useCover) { %}
            
            <a href="javascript:void(0)" onclick="setCover(this,'{%=file.name%}','{%=file.project_id%}'); return false;" class="btn btn-default">
                <i class="icon-good1"></i>
                <span>Обложка</span>
            </a>
            {% } %}
            </div>
        </td>
    </tr>
    {% } %}
</script>

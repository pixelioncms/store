tinymce.PluginManager.add('pixelion', function (editor, url) {
   // console.info('Tinymce pixelion plugin load',editor);
   // var lang = tinymce.util.I18n.getCode();
   // console.log(tinymce.translate('test'));

    //var editor = tinymce.activeEditor;

    editor.addButton('pixelion', {
        text: 'Pixelion',
        tooltip: 'Pixelion',
        stateSelector: 'span.quick-note', // кнопка в тулбаре будет подсвечиваться для заданных элементов
        icon: false,      // Суффикс иконки из набора TinyMCE: 'mce-ico mce-i-editimage'
        classes: 'quick-note',  // Класс будет добавлен для блока кнопки с префиксом 'mce-'
        shortcut: 'Ctrl+Q',
        onclick: function() {
            // Open window
            editor.windowManager.open({
                title: 'Pixelion plugin',
               // width: 500,
                //height: 240,
                //url: 'https://pixelion.com.ua',
                body: [
                    {type: 'textbox', name: 'title2', label: 'Title2'},
                    {
                        type: 'textbox',
                        name: 'text',
                        label: '',
                        value: 'Поместить текст в окно редактирования',
                        multiline: false, // textarea
                    },
                    {type: 'listbox',
                        name: 'align',
                        label: 'align',
                        'values': [
                            {text: 'Left', value: 'left'},
                            {text: 'Right', value: 'right'},
                            {text: 'Center', value: 'center'}
                        ]
                    }
                ],
                onsubmit: function(e) {
                    // Insert content when the window form is submitted
                    var text = e.data.title2;
                    var align = e.data.align;
                    editor.insertContent('<span>' + text + '</span>');
                    editor.insertContent('<span>' + align + '</span>');
                }


            }, {
                asd: 1
            });

            //editor.windowManager.confirm("Do you want to do something", function(s) {
            //    if (s)
            //        editor.windowManager.alert("Ok");
            //    else
            //        editor.windowManager.alert("Cancel");
           // });
        }
    });

    editor.on('NodeChange', function(e) {
        var quickNoteElement = editor.dom.is(e.element, 'span[data-quick-note]');
        if (quickNoteElement) {
            var quickNoteTooltip = document.getElementById('quick-note-tooltip'),
                quickNoteText = e.element.getAttribute('data-quick-note');

            if (quickNoteTooltip) {
                quickNoteElement.innerHTML = quickNoteText;
            }
        }
    });

    editor.addContextToolbar(
        'span[data-quick-note]',
        'link unlink | undo redo | pixelion'
    );
    editor.addCommand('mycommand', function() {
        console.log('mycommand');
    });
    editor.addMenuItem('pixelion', {
        text: 'Pixelion website',
        context: 'tools',
        menu:[{
            text: 'Pixelion website',
            context: 'pixelion',
            onclick: function() {
                console.log('ss');
                //var selectedText = editor.selection.getContent();
                //editor.insertContent('<code type="php">' + selectedText + '</code>');
                editor.windowManager.open({
                    title: 'Pixelion website',
                    url: 'https://pixelion.com.ua',
                    width: 800,
                    height: 600,
                    buttons: [{
                        text: 'Close',
                        onclick: 'close'
                    }]
                });
            }
        },
            {
                text: 'Pixelion website2',
                context: 'pixelion',
                onclick: function() {
                    console.log('ss');
                    //var selectedText = editor.selection.getContent();
                    //editor.insertContent('<code type="php">' + selectedText + '</code>');
                    editor.windowManager.open({
                        title: 'Pixelion website',
                        url: 'https://pixelion.com.ua',
                        width: 800,
                        height: 600,
                        buttons: [{
                            text: 'Close',
                            onclick: 'close'
                        }]
                    });
                }
            }],
        onclick: function() {
            console.log('ss');
            //var selectedText = editor.selection.getContent();
            //editor.insertContent('<code type="php">' + selectedText + '</code>');
            editor.windowManager.open({
                title: 'Pixelion website',
                url: 'https://pixelion.com.ua',
                width: 800,
                height: 600,
                buttons: [{
                    text: 'Close',
                    onclick: 'close'
                }]
            });
        }
    });


    editor.addMenuItem('pixelion2', {
        text: 'Pixelion website',
        context: 'pixelion2',
        onclick: function() {
            console.log('ss');
            //var selectedText = editor.selection.getContent();
            //editor.insertContent('<code type="php">' + selectedText + '</code>');
            editor.windowManager.open({
                title: 'Pixelion website',
                url: 'https://pixelion.com.ua',
                width: 800,
                height: 600,
                buttons: [{
                    text: 'Close',
                    onclick: 'close'
                }]
            });
        }
    });
});
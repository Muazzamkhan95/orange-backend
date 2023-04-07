{{-- <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.2.7/purify.min.js"></script>

  <script type="text/javascript" src="{{ asset('editor/js/froala_editor.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/align.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/char_counter.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/code_beautifier.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/colors.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/draggable.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/entities.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/font_size.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/line_breaker.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/inline_style.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/lists.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/quote.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/table.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/save.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/special_characters.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('editor/js/plugins/word_paste.min.js')}}"></script>

<script>
  (function () {
    new FroalaEditor("#edit")
  })()
</script> --}}
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
<script src="{{ asset('texteditor/trumbowyg.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('texteditor/ui/trumbowyg.min.css')}}">

<script>
    $('#trumbowyg-demo').trumbowyg();
    $('#trumbowyg-demo1').trumbowyg();
</script>

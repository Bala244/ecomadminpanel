<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="./assets/drag-drop/dist/image-uploader.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
<script>
    $('a').click(function(event) {
      var href = $(this).attr('href');
      console.log(href)
        $('#pjax-container').fadeOut(100, function() {
                $.pjax({
                  url: href,
                  container: '#pjax-container',
                  fragment: '#pjax-container'
                })
            })
        $(document).on('pjax:success', function() {
            $('#pjax-container').fadeIn(100);
        });
        return false;
    });
</script>

<script>
  $('.toast-close').click(function(){
    $(this).remove();
  });
  $(document).ready(function () {
    $('.datatable').DataTable();
  });
</script>
  </body>
</html>

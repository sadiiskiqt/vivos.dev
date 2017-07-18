<div class="reveal large" id="{!! $modal_id !!}" data-reveal>   
	<h1>Page Preview Images</h1>

	<div class="grid row collapse">
		
	</div>
	<button class="close-button" data-close aria-label="Close modal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
	<button class="button alert" data-close aria-label="Close modal" type="button">
		Done
	</button>
</div>
<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function(event) {
		(function() {	

			var reveal = {{ $modal_id }};
			console.log({{ $modal_id }});
			var current_id = '{{$preview_thumb_id}}';
			var downloaded = false;
			$(reveal).on('open.zf.reveal', function(ev) {
				if(!downloaded){
					$.ajax({
						url:'/admin/pages/related-images',
						context:$(this)
					}).done(function(response, status) {
						downloaded = true;
						var items = [];
						$.each(response, function(index, image) {
							if (current_id != image.id){
								items.push('<div class="columns large-1 medium-2 small-3"><a data-image-id="'+image.id+'" class="thumb"><img src="'+image.thumbnail+'"></a></div>')
							}
							else{
								items.push('<div class="columns large-1 medium-2 small-3"><a data-image-id="'+image.id+'" class="thumb featured"><img src="'+image.thumbnail+'"></a></div>')

							}
						});
						items.push('<div class="columns large-1 medium-2 small-3"></div>');
						$(reveal).find('.grid').html(items);
					});
				}
			});
			$(document).on('click', '.reveal .grid .columns .thumb', function(ev) {
				ev.preventDefault();
				$('.reveal .grid .columns .thumb').removeClass('featured');
				$(this).addClass('featured');
				if($('#preview_thumb_id img').length>0){
					$('#preview_thumb_id img').attr('src' , $(this).find('img').attr('src'));
				}
				else{
					var tmb = $('<img />', { 
						src: $(this).find('img').attr('src')
					});
					$('#preview_thumb_id').html(tmb);
				}
				$('[name="preview_thumb_id"]').val($(this).attr('data-image-id'));
			});
			$(document).on('click', '.remove-thumb', function(ev) {
				ev.preventDefault();
				$('#preview_thumb_id img').attr('src' , '');
				$('[name="preview_thumb_id"]').val(null);
			})
		})()
	});	
</script>
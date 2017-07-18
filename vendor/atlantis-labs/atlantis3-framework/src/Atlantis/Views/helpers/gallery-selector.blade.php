<div class="row gal-selector">	
	<div class="columns medium-4">
		<label for="gallery_id">Gallery
			{!! Form::select('gallery_id', $aGalleriesSelect, $selected_gallery, ['id' => 'gallery_id']) !!}
		</label>
	</div>
	<div class="column medium-4 end gal-selector-actions">
		<label for="">
			actions
		</label>
		<a href="#" target="_blank" class="edit-gal alert button disabled">Edit This Gallery</a>
		<a href="/admin/media/gallery-add" target="_blank" class="button">Create New Gallery</a>
		<a target="_blank" class="button refresh-gal">Refresh Gallery List</a>
	</div>
</div>
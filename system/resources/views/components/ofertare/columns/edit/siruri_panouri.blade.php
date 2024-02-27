@props(['section' => '2021', 'column', 'item', 'types' => \App\Models\Ofertare\ColoanaTabelAFM::types(), 'form' => true])

@if($form)
<div class="form-group">
    @isset($label)
        <label class="col-md-3 control-label">{{ $label }}</label>
        <div class="col-md-7 select2-container block">
    @else
        <div class="col-md-offset-3 col-md-7 select2-container block">
    @endisset
@endif
        <div class="fields-group">
        	@foreach($item->siruri_panouri ?? [1 => '1x1'] as $sir)
	            <div class="form-group">
	                <select class="form-control" name="siruri_panouri[]">
	                    @for ($i = 1; $i <= 30; $i++)
	                        <option value="1x{{ $i }}" @selected($sir === '1x'.$i )>1x{{ $i }}</option>
	                    @endfor
	                </select>
	                <button class="btn btn-danger img-rounded delete-item mt-3" onclick="this.closest('.form-group').remove()">
	                	<b style="padding-right: 5px">-</b> Sterge
	                </button>
	                <hr class="mt-8" style="margin-bottom: 2rem">
	            </div>
            @endforeach
        </div>
        <button class="btn btn-success img-rounded add-item control-label" onclick="addSirPanouri(this.previousElementSibling)">
        	<b style="padding-right: 5px">+</b> Adauga
        </button>
    </div>
@if($form)
</div>
@endif

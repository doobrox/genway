
@extends('ofertare.layouts.app')

@section('styles')
<style>
    .error-border {
        border: 2px solid #FF8F8F;
        
    }
    .hidden {
        display: none;
    }
</style>
@endsection

@section('content')
     <div class="alert alert-danger alert-dismissible fade in hidden error-border">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <p class="error-alert"></p>
    </div>
	<div class="table-responsive" style="overflow: auto;max-height: 82vh;">
		<table class="table table-bordered table-hover">
			<thead>
				<tr class="bg-grey" style="position: sticky; top:-1px; z-index:9">
                    <th>Campuri tabel</th>
                    @foreach($coloane_editabile as $item)
                        <th>{{ $item }}</th>
                    @endforeach

                    @foreach($roluri as $rol)
                        <th>{{ $rol->name }}</th>
                    @endforeach
				</tr>
			</thead>
			<tbody>
                @foreach($coloane as $coloana)
                    <tr 
                        id="{{ $coloana->nume }}"
                        data-route="{{ route('ofertare.afm.description.update.column', [$coloana->nume]) }}"
                    >
                        <th>{{ $coloana->titlu }}</th>

                        @foreach($coloane_editabile as $item)
                            <td data-edit="{{ $item }}">{!! $coloana->descriere ? nl2br($coloana->descriere->{$item}) : null !!}</td>
                        @endforeach

                        @foreach($roluri as $rol)
                            <td>
                                @if($rol->hasPermissionTo('afm.2021.'.$coloana->nume.'.edit'))
                                    E
                                @elseif($rol->hasPermissionTo('afm.2021.'.$coloana->nume.'.view'))
                                    V
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
		</table>
	</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('td[data-edit]').on('dblclick', function() {
                const currentTd = $(this);
                let textarea = $('<textarea class="add-text">' + currentTd.text().trim() + '</textarea>');
                currentTd.html(textarea);
                onBlurHandler(currentTd, textarea);
            });
        });

        function onBlurHandler(currentTd, textarea) {
            $('.table td[data-edit] .add-text').blur(function() {

                $.ajax({
                    url: $(this).closest('tr').data('route'),
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {[currentTd.data('edit')]: $(this).val()},
                    dataType: 'json',
                    success: function(data) {

                        if (Object.keys(data.errors).length) {
                            textarea.addClass('error-border');
                            let errorMessages = '';

                            for (const [key, value] of Object.entries(data.errors)) {
                                errorMessages += `${value[0]}`;
                            }

                            $('.alert-danger').removeClass('hidden');
                            $('.error-alert').text(errorMessages)
                        } else {
                            textarea.removeClass('error-border');
                            $('.alert-danger').addClass('hidden');
                            currentTd.html(data.value);
                        }
                    },
                    error: function(xhr, status, error) {
                        //console.error('Eroare: ', xhr.responseText);
                    }
                });
            });
        } 
    </script>
@endsection

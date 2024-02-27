@props(['permissions' => [], 'section' => null, 'name' => null, 'id' => null, 'i' => 1])



@if(is_array($id))
    <div style="padding-left: 1rem;">
        {{-- {{ $i > 1 ? '3' : '' }} --}}
        <div class="clearfix"></div>
        @if($i > 1)
            <h5 class="col-sm-6 col-xs-12">{{  __('Coloana') }} <b>{{ strtolower(str_replace('_', ' ', $name)) }}</b></h5>
        @else
            <h5><b>{{  __('Subsectiunea') }} {{ strtolower(str_replace('_', ' ', $name)) }}</b></h5>
        @endif
        @php $keys = $id; @endphp
        
        @foreach($keys as $subname => $id)
                <x-ofertare.permission-section :permissions="$permissions[$name] ?? []" :name="$subname" :id="$id" :i="$i+1" />
        @endforeach
    </div>
@else

    @if($name == '*')
        <div>
            <label>
                <input type="checkbox" id="permission-{{ $id }}" name="permissions[]" value="{{ $id }}"
                    @checked(old('permissions.*', $permissions[$name] ?? '') == $id)
                    data-check-all="{{ $section }}"> 
                <span>{{ __('All') }}</span>
            </label>
        </div> 
    @else
        @if($i > 2 && in_array($name, ['view','edit']))
            <div class="col-sm-2 col-xs-4">
                <label>
                    <span title="{{ ucfirst(__($name)) }}">
                        <i class="fa fa-{{ $name == 'view' ? 'eye' : $name }}"></i>
                    </span>
                    <input type="checkbox" id="permission-{{ $id }}" name="permissions[]" value="{{ $id }}"
                        @checked(old('permissions.*', $permissions[$name] ?? '') == $id)
                        data-be-checked="{{ $section }}"> 
                </label>
            </div>
        @else
            <div class="col-md-3 col-xs-6">
                <label>
                    <input type="checkbox" id="permission-{{ $id }}" name="permissions[]" value="{{ $id }}"
                        @checked(old('permissions.*', $permissions[$name] ?? '') == $id)
                        data-be-checked="{{ $section }}"> 
                    <span>{{ ucfirst(__(str_replace('_', ' ', $name))) }}</span>
                </label>
            </div>
        @endif
    @endif
@endif

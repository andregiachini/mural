<div>
    @if ($show_success && $finished)
        @include('livewire.crud.success')
    @endif

    @if (!$finished)
        <div class="form-control mb-4 pb-4">
            @foreach ($fields as $key => $field)
                @switch(@$field['type'])
                @case('boolean')
                    <label class="cursor-pointer label flex">
                        <span class="label-text">{{ $field['label'] }} <br /><span class="text-gray-400">{{ @$field['placeholder'] }}</span></span> 
                        <div>
                            <input wire:model="{{ $key }}" type="checkbox" class="toggle toggle-primary"> 
                            <span class="toggle-mark"></span>
                        </div>
                    </label>
                    @break
                @case('select')
                    <label class="label">
                        <span class="label-text">{{ $field['label'] }}</span> 
                        <a href="#" class="label-text-alt"></a>
                    </label> 
                    <select wire:model="{{ $key }}" class="select select-bordered w-full @error($key) select-error @enderror">
                        <option value=""></option> 
                        @foreach ($field['options'] as $info)
                            <option value="{{ $info['id']}}">{{ $info['text']}}</option> 
                        @endforeach
                    </select> 
                    @break
                @case('file')
                    <label class="label">
                        <span class="label-text">{{ $field['label'] }}</span> 
                        <a href="#" class="label-text-alt"></a>
                    </label>
                    <div
                        wire:ignore
                        x-data="{pond: null}"
                        x-init="
                            pond = FilePond.create($refs.input);
                            pond.setOptions({
                                allowMultiple: true,
                                labelIdle:'{{ @$field['placeholder'] }}',
                                labelFileProcessingComplete: 'Upload finalizado',
                                server: {
                                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                                        @this.upload('{{ $key }}', file, load, error, progress)
                                    },
                                    revert: (filename, load) => {
                                        @this.removeUpload('{{ $key }}', filename, load)
                                    },
                                },
                            });
                    ">
                        <input type="file" name="{{ $key }}" x-ref="input">
                    </div>
                    @break
                @case('poll')
                    <div class="flex w-full">
                        <div class="w-1/2">
                            <label for="{{ $key }}" class="label">
                                <span class="label-text">{{ $field['label'] }}</span>
                            </label>
                            <textarea wire:model="{{ $key }}" name="{{ $key }}" placeholder="{{ @$field['placeholder'] }}" class="textarea textarea-bordered h-40 w-full @error($key) textarea-error @enderror"></textarea>
                        </div>
                        <div class="w-1/2">
                            <label class="label">
                                <span class="label-text">Visualização</span>
                            </label>
                            <div x-data="{ content: null }" x-init="{ content: @entangle('data.poll') }" class="w-full p-2 bg-gray-200 h-40 overflow-scroll">
                                <div x-on:blur="content = $event.target.innerHTML">
                                    {!! $poll_view !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @break
                @case('textarea')
                    <label for="{{ $key }}" class="label">
                        <span class="label-text">{{ $field['label'] }}</span>
                    </label>
                    <textarea wire:model="{{ $key }}" name="{{ $key }}" placeholder="{{ @$field['placeholder'] }}" class="textarea textarea-bordered h-24 @error($key) textarea-error @enderror"></textarea>
                    @break
                @default
                    <label for="{{ $key }}" class="label">
                        <span class="label-text">{{ $field['label'] }}</span>
                    </label>
                    <input wire:model="{{ $key }}" type="text" name="{{ $key }}" placeholder="{{ @$field['placeholder'] }}" class="input input-bordered @error($key) input-error @enderror" />
                    @break
                @endswitch

                @error($key)
                    <label class="label">
                        <span class="label-text-alt text-red-500">{{ $message }}</span>
                    </label>
                @enderror
            @endforeach
        </div>

        @if (isset($data['id']) && !isset($create))
            <button wire:click="update()" class="btn btn-primary float-right">Salvar</button>
            <button wire:click="cancel()" class="btn float-right mr-6">Cancelar</button>
        @else
            <button wire:click="store()" class="btn btn-wide btn-primary">Criar</button>
        @endif
    @endif
</div> 
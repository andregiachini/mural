<div class="pt-10">
    <!--
        Comments list
        Source: https://tailwindcomponents.com/component/comment-section
    -->
    <div class="antialiased w-full">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Comentários</h3>
        @forelse ($items as $comment)
            <div class="space-y-4">
                <div class="flex pb-4">
                    <div class="flex-shrink-0 mr-3">
                        <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10"
                            src="https://images.unsplash.com/photo-1604426633861-11b2faead63c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=200&h=200&q=80"
                            alt="">
                    </div>
                    <div class="flex-1 border rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
                        <strong>{{ $comment['user']['name'] }}</strong> <span class="text-xs text-gray-400">{{ $comment['created_at'] }}</span>
                        <p class="text-sm">{!! Str::markdown($comment['content']) !!}</p>
                        <div class="mt-4 flex items-center hidden">
                            <div class="flex -space-x-2 mr-2">
                                <img class="rounded-full w-6 h-6 border border-white"
                                    src="https://images.unsplash.com/photo-1554151228-14d9def656e4?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=100&h=100&q=80"
                                    alt="">
                                <img class="rounded-full w-6 h-6 border border-white"
                                    src="https://images.unsplash.com/photo-1513956589380-bad6acb9b9d4?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=100&h=100&q=80"
                                    alt="">
                            </div>
                            <div class="text-sm text-gray-500 font-semibold">
                                5 Replies
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Nenhum comentário por enquanto</p>
        @endforelse
    </div>

    <div class="w-full pt-4 mb-6">
        <hr class="text-gray-400" />
    </div>

    <!--
        Comment form
        Source: https://tailwindcomponents.com/component/comment-form
    -->
    <div class="antialiased w-full">
        <div class="space-y-4">
            <div class="flex">
                <div class="flex-shrink-0 mr-3">
                    <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10"
                        src="https://images.unsplash.com/photo-1604426633861-11b2faead63c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=200&h=200&q=80"
                        alt="">
                </div>
                <div class="flex-1 rounded-lg px-1 py-2 sm:px-6 sm:py-4 leading-relaxed">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-full px-3 mb-2 mt-2">
                            <div class="form-control pb-3">
                                <label class="label">
                                  <span class="label-text">Comentar</span>
                                </label> 
                                <textarea wire:model="content" name="content" class="textarea h-24 textarea-bordered @error('content') textarea-error @enderror" placeholder="Escreva seu comentário"></textarea>
                            </div>
                        </div>
                        <div class="w-full md:w-full flex items-start md:w-full px-3">
                            <div class="flex items-start w-1/2 text-gray-700 px-2 mr-auto">
                                <svg fill="none" class="w-5 h-5 text-gray-600 mr-1" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs md:text-sm pt-px text-gray-400">Você pode usar markdown para formação.</p>
                            </div>
                            <div class="-mr-1">
                                <button wire:click="store()" class="btn btn-primary">Comentar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
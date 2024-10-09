<x-app-layout>
    <section class="py-5 position-relative">
        <div class="container">
            <header class="mb-4">
                <form class="row align-items-center">
                    <div class="col-md-auto">
                        <div class="btn-group btn-group-sm">
                            @foreach(['date', 'deal', 'state'] as $group)
                                <button
                                    class="btn btn-outline-dark{{ request('group_by', 'date') == $group ? ' active' : '' }}"
                                    name="group_by" value="{{ $group }}"
                                    title="{{ __('Group by :attribute', ['attribute' => $group]) }}">
                                    <svg fill="currentColor" width="16" height="16">
                                        <use xlink:href="{{ asset('icons/navigation.svg#' . $group) }}"></use>
                                    </svg>
                                    @if (request('group_by', 'date') == $group)
                                        <span class="ms-2">
                                            {{ __('Group by :attribute', ['attribute' => $group]) }}
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-auto ms-auto">
                        <div class="row gx-2">
                            <div class="col-auto">
                                <select name="user" id="user" class="form-control" onchange="this.form.submit()">
                                    <option value="">{{ __('Choose the user') }}</option>
                                    @foreach($users as $user)
                                        <option
                                            value="{{ $user->id }}"
                                            {{ request('user') == $user->id ? 'selected' : '' }}
                                        >{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-date-range-picker class="col-auto"/>
                        </div>
                    </div>
                </form>
            </header>

            @includeIf('dashboard.leads.group-by.' . request('group_by', 'date'))

            <div class="d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        </div>
    </section>
</x-app-layout>

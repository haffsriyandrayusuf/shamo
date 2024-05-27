<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category') }}
        </h2>
    </x-slot>
    <x-slot name="script">
        <script>
            // AJAX Datatables
            var datatable = $('#crudTable').DataTable({
                ajax: {
                    url: "{{ route('dashboard.category.index') }}",
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '5%'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '25%'
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $('td:eq(0)', row).html(dataIndex + 1);
                }
            })
        </script>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('dashboard.category.create') }}" class="px-4 py-2 bg-green-500 hover:bg-green-700 rounded text-white font-bold shadow-lg">
                    + Create Category
                </a>
            </div>
            <div class="shadow overflow-hidden sm:rounded-sm">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="crudTable" class="w-full table-auto">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
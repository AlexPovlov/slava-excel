<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form enctype="multipart/form-data" action="{{ route('excel.store') }}" method="post">
                        <label for="formFile" class="form-label">Excel file</label>
                        <input class="form-control" type="file" id="formFile" name="file">
                        @csrf
                        @error('file')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <input type="submit" class="mt-3 btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

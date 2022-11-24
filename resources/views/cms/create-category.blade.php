<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create Category</h3>
                    </div>

                    <form id="forme_rest">

                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <label for="name">Titel</label>
                                <input type="text" class="form-control @if($errors->any()) is-invalid @endif " id="title"
                                    name="titel" placeholder="Titel" >
                            </div>


                            <div class="form-group">
                                <label for="name">description</label>
                                <input type="text" class="form-control @if($errors->any()) is-invalid @endif " id="description" name="description"
                                    placeholder="description" >
                            </div>

                            <div class="form-group">
                                <label>status</label>
                                <select class="form-control status" style="width: 100%;" id="status">
                                    <option value="Visible">Visible</option>
                                    <option value="InVisible">InVisible</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label>store_id</label>
                                <select class="form-control roles" style="width: 100%;" id="store_id">
                                    @foreach ($store as $store)
                                    <option value="{{$store->id}}">{{$store->name_en}}</option>
                                    @endforeach
                                </select>
                            </div>





                        </div>
                        <div class="card-footer">
                            <button type="button" onclick="performstore()"
                                class="btn btn-primary">send</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
</section>

<script src="https://unpkg.com/axios@0.27.2/dist/axios.min.js"></script>

<script>
    function performstore(){
        let formData=new FormData ();
        formData.append('title',document.getElementById('title').value);
        formData.append('description',document.getElementById('description').value);
        formData.append('status',document.getElementById('status').value);
        formData.append('store_id',document.getElementById('store_id').value);

        axios.post('/category', formData)
        .then(function (response) {
            console.log(response);
      toastr.success(response.data.message);
      window.location.href='/category'
      document.getElementById('forme_rest').reset();

        })
        .catch(function (error) {
            console.log(error);
      toastr.error(error.response.data.message);
        });
    }



//

</script>

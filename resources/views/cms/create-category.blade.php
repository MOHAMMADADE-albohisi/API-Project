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


                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-md-1">
                                        <span>image</span>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <fieldset class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image">
                                                <label class="custom-file-label" for="image">image</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
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
        formData.append('image',document.getElementById('image').files[0]);
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

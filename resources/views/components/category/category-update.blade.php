<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryNameUpdate">
                                <input  id="updateID" hidden>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>




<script>
    async function fillUpUpdateForm(id) {
        document.getElementById("updateID").value=id;
        showLoader();
        let res = await axios.post("/category-edit",{id:id})
        hideLoader();
        document.getElementById("categoryNameUpdate").value = res.data["name"];
    }

    async function update() {
       let categoryName = document.getElementById("categoryNameUpdate").value;
       let updateID = document.getElementById("updateID").value;
       if(categoryName.length === 0){
        errorToast("Category Name Required !")
       }
       else{
        document.getElementById("update-modal-close").click();
        showLoader();
        let res = await axios.post("/category-update", {name:categoryName,id:updateID});
        hideLoader();
        if(res.status === 200 && res.data["status"] === "success"){
            document.getElementById("update-form").reset();
            successToast(res.data["message"]);
            //successToast("update");
            await getList();
        }
        else{
            errorToast(res.data["message"]);
        }
       }
    }
</script>

<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">


                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">

                                <label class="form-label mt-2">Price</label>
                                <input type="number" class="form-control" id="productPriceUpdate">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnitUpdate">
                                <br/>
                                <img class="w-15" id="oldImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>
                                <label class="form-label mt-2">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])"  type="file" class="form-control" id="productImgUpdate">

                                <input type="text"  id="updateID" hidden>
                                <input type="text"  id="filePath" hidden>


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
    async function UpdateFillCategoryDropDown() {
        let res = await axios.get("/category-list")
        res.data.forEach(function (item,i){
            let option = `<option value="${item['id']}">${item['name']}</option>`
            $("#productCategoryUpdate").append(option);
        })
    }
    async function FillUpUpdateForm(id, filePath) {
        document.getElementById("updateID").value=id;
        document.getElementById("filePath").value=filePath;
        document.getElementById("oldImg").src=filePath;
        
        showLoader();
        await UpdateFillCategoryDropDown();
        let res = await axios.post("/product-edit",{id:id})
        hideLoader();
        document.getElementById("productNameUpdate").value=res.data["name"];
        document.getElementById("productPriceUpdate").value=res.data["price"];
        document.getElementById("productUnitUpdate").value=res.data["unit"];
        document.getElementById("productCategoryUpdate").value=res.data["category_id"];
    }

    async function update() {
        let productCategoryUpdate=document.getElementById('productCategoryUpdate').value;
        let productNameUpdate = document.getElementById('productNameUpdate').value;
        let productPriceUpdate = document.getElementById('productPriceUpdate').value;
        let productUnitUpdate = document.getElementById('productUnitUpdate').value;
        let updateID=document.getElementById('updateID').value;
        let filePath=document.getElementById('filePath').value;
        let productImgUpdate = document.getElementById('productImgUpdate').files[0];

        if(productCategoryUpdate === 0){
            errorToast("Product Category Required !")
        }
        else if(productNameUpdate === 0){
            errorToast("Product Name Required !")
        }
        else if(productUnitUpdate === 0){
            errorToast("Product Unit Required !")
        }
        else if(productPriceUpdate === 0){
            errorToast("Product Price Required !")
        }
        else{
            document.getElementById('update-modal-close').click();
            let formData=new FormData();
            formData.append('img',productImgUpdate)
            formData.append('id',updateID)
            formData.append('name',productNameUpdate)
            formData.append('price',productPriceUpdate)
            formData.append('unit',productUnitUpdate)
            formData.append('category_id',productCategoryUpdate)
            formData.append('file_path',filePath)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            
            showLoader();
            let res = await axios.post("/product-update", formData,config)
            hideLoader();
            if(res.status === 200 && res.data["status"]=== "success"){
                successToast(res.data["message"]);
                document.getElementById("save-form").reset();
                await getList();
            }
            else{
                errorToast(res.data["message"]);
            }
        }
    }
</script>
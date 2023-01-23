<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous"
    >

    <title>{{ config('app.name') }}</title>
</head>
<body>
<div class="container" id="app">
    <div class="row justify-content-center">
        <div class="col-6 align-self-center">
            <div class="card mt-5">
                <div class="card-header">
                    {{ config('app.name') }}
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="address">Address*</label>
                            <input v-model="originalAddress.address" type="text" class="form-control" id="address"
                                   placeholder="ex: 716 Oak St" required>
                        </div>
                        <div class="form-group">
                            <label for="zip">Zip 5</label>
                            <input v-model="originalAddress.zip" type="text" class="form-control" id="zip"
                                   placeholder="ex: 61272">
                        </div>
                        <div class="form-group">
                            <label for="apartment">Apartment</label>
                            <input v-model="originalAddress.apartment" type="text" class="form-control" id="apartment"
                                   placeholder="ex: 22">
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input v-model="originalAddress.city" type="text" class="form-control" id="city"
                                   placeholder="ex: New Boston">
                        </div>

                        <div class="form-group">
                            <label for="state">State*</label>
                            <input v-model="originalAddress.state" type="text" class="form-control" id="state"
                                   placeholder="ex: IL">
                        </div>

                        <div class="alert alert-danger fade show" role="alert" v-show="errorMessage">
                            <strong>Error happened!</strong> @{{ errorMessage }}
                            <button type="button" class="close" aria-label="Close" @click="clearErrorMessage">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="alert alert-success fade show" role="alert" v-show="successMessage">
                            <strong>Successful!</strong> @{{ successMessage }}
                            <button type="button" class="close" aria-label="Close" @click="clearSuccessMessage">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="form-group row" v-show="normalizedAddress.address">
                            <label for="normalizedAddress" class="col-sm-4 col-form-label">Normalized Address:</label>
                            <div class="col-sm-8">
                                <input v-model="normalizedAddressAsString"
                                       type="text"
                                       readonly
                                       class="form-control-plaintext"
                                       id="normalizedAddress">
                            </div>
                        </div>
                        <button type="button"
                                class="btn btn-secondary btn-small"
                                @click.prevent="normalize"
                                :disabled="isLoading"
                        >
                            <span class="spinner-border spinner-border-sm"
                                  v-show="isLoading"
                                  role="status"
                            ></span>
                            Normalize

                        </button>

                        <div class="btn-group float-right" role="group" v-show="normalizedAddress.address">
                            <button type="button"
                                    class="btn btn-outline-primary"
                                    @click.prevent="submit(this.originalAddress)"
                                    :disabled="isLoading"
                            >
                                <span class="spinner-border spinner-border-sm"
                                      v-show="isLoading"
                                      role="status"
                                ></span>
                                Submit Original
                            </button>
                            <button type="button"
                                    class="btn btn-primary"
                                    @click.prevent="submit(this.normalizedAddress)"
                                    :disabled="isLoading"
                            >
                                <span class="spinner-border spinner-border-sm"
                                      v-show="isLoading"
                                      role="status"
                                ></span>
                                Submit Normalized
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.3/axios.min.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<script>
    const {createApp} = Vue

    createApp({
        data() {
            return {
                originalAddress: {
                    address: '',
                    zip: '',
                    apartment: '',
                    city: '',
                    state: '',
                },
                normalizedAddress: {
                    address: '',
                    zip: '',
                    city: '',
                    state: '',
                },
                isLoading: false,
                errorMessage: '',
                successMessage: '',
            }
        },
        computed: {
            normalizedAddressAsString() {
                const addressObj = this.normalizedAddress;
                return `${addressObj.address}, ${addressObj.city}, ${addressObj.state} ${addressObj.zip}`;
            }
        },
        methods: {
            normalize() {
                this.clearErrorMessage();
                this.clearSuccessMessage();
                this.clearNormalized();
                this.isLoading = true;

                axios.post('/addresses/normalize', this.originalAddress)
                    .then((response) => {
                        const adrObj = response.data.address;
                        this.normalizedAddress.address = adrObj['Address2'];
                        this.normalizedAddress.city = adrObj['City'];
                        this.normalizedAddress.state = adrObj['State'];
                        this.normalizedAddress.zip = adrObj['Zip5'];
                    })
                    .catch((e) => {
                        this.errorMessage = e.response.data.message;
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            submit(addressObj) {
                this.isLoading = true;

                axios.post('/addresses', addressObj)
                    .then((response) => {
                        const type = addressObj.apartment ? 'Original ' : 'Normalized ';
                        this.successMessage = type + 'address was stored in db!';
                        this.clearErrorMessage();
                        this.clearOriginal();
                        this.clearNormalized();
                    })
                    .catch((e) => {
                        this.errorMessage = e.response.data.message;
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            clearErrorMessage() {
                this.errorMessage = '';
            },
            clearSuccessMessage() {
                this.successMessage = '';
            },
            clearOriginal() {
                this.originalAddress = {
                    address: '',
                    zip: '',
                    apartment: '',
                    city: '',
                    state: '',
                };
            },
            clearNormalized() {
                this.normalizedAddress = {
                    address: '',
                    zip: '',
                    apartment: '',
                    city: '',
                    state: '',
                };
            }
        }
    }).mount('#app')
</script>
</body>
</html>

<template>
    <section class="main">
        <div class="row">
            <div class="layer offset-md-3 col-md-6">
                <div class="m-portlet m-portlet--danger m-portlet--head-solid-bg m-portlet--rounded">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="la la-thumb-tack"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    SESSION PORTAL 
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-center">
                                    <i class="la la-clock-o text-danger"></i> This is an online session portal
                                </h5>
                                <h1 class="text-center digital-clock">
                                    00:00:00
                                </h1>
                                <h5 class="text-center">
                                    Please click "Sign In" for signing in, and "Sign Off" for signing out
                                </h5>
                                <div class="text-center mb-2">
                                    <b-form-select v-model="subject" :options="options" class="hidden"></b-form-select>
                                </div>
                                <div class="text-center">
                                    <button type="button" v-bind:class="{active: isActive}" @click="showModal" action-taken="signin" class="action-btn btn btn-md">
                                        Sign In
                                    </button>
                                    <button type="button" v-bind:class="{active: isActive2}" @click="showModal2" action-taken="signout" class="action-btn btn btn-md">
                                        Sign Off
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div v-bind:class="{hidden: isQRcode}" class="camera-layer">
                                    <i id="dummy-pic" ref="dummy-pic" v-bind:class="{hidden: isSuccess}" class="flaticon-profile-1"></i>
                                    <div id="custom-pic" v-bind:class="{hidden: isFailed}">
                                        <img ref="avatarImg" :src="imgSource" />
                                        <h6>
                                            <span name="firstname">{{ firstname }}</span> <span name="lastname">{{ lastname }}</span><br/>
                                            <span name="stud_no">{{ stud_no }}</span>
                                        </h6>
                                    </div>
                                </div>
                                <div v-bind:class="{hidden: isnotQRcode}" class="camera-layer2 text-center">
                                    <div class="form-group m-form__group">
                                        <input :disabled="isDisabled == 1" v-model="id_number" @keyup="searchMethod" type="text" class="form-control m-input" id="id_number" name="id_number" placeholder="enter id number">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <input :disabled="isDisabled == 1" v-bind:class="{hidden: isHidden}" v-model="mobile_number" type="text" class="form-control m-input" id="mobile_number" name="mobile_number" placeholder="enter mobile number">
                                    </div>
                                    <button :disabled="isDisabled == 1 || (isSearch == false && mobile_number == '')" @click="requestOTP" id="request-otp-btn" ref="request-otp-btn" type="button" class="btn btn-md">
                                        request an OTP
                                    </button>
                                    <div>
                                        <input v-model="otp_1" type="text" maxlength="1" name="otp_1" class="form-control box col">
                                        <input v-model="otp_2" type="text" maxlength="1" name="otp_2" class="form-control box col">
                                        <input v-model="otp_3" type="text" maxlength="1" name="otp_3" class="form-control box col">
                                        <input v-model="otp_4" type="text" maxlength="1" name="otp_4" class="form-control box col">
                                        <input v-model="otp_5" type="text" maxlength="1" name="otp_5" class="form-control box col">
                                    </div>
                                </div>
                                <a href="javascript:;" @click="showQR" class="text-center qr-problem">{{ (isQRcode == true) ? 'use QR Code?' : 'having problem with QR Code?' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-bind:class="{hidden: isFailed}" class="alert alert-danger alert-dismissible fade show m-alert m-alert--air m-alert--outline m-alert--outline-2x" role="alert">
                    <strong>
                        Well done <span name="firstname">{{ firstname }}</span>! 
                    </strong>
                    You have successfully <span class="actions">{{ actions }}</span>.
                </div>
            </div>
        </div>
        <div class="row">
            <audio ref="audio1" :src="mp3Source1" preload="auto"></audio>
            <audio ref="audio2" :src="mp3Source2" preload="auto"></audio>
        </div>
            

        <b-modal ref="my-modal" @hidden="resetModal" centered hide-footer title="QR Cam">
            <div class="video-layer">
                <qrcode-stream :camera="camera" @decode="onDecode" @init="onInit" />
            </div>
            <button @click="switchCamera" class="btn btn-camera" alt="switch camera">
                <i class="fa fa-camera"></i> Switch Camera
            </button>
        </b-modal>
    </section>
  
    
</template>



<script>
    import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'

    const axios = require('axios');
    
    export default {

        components: { QrcodeStream },

        data () {
            return {
                subject: 'select a subject',
                options: [],
                mobile_number: '',
                id_number: '',
                otp_1: '',
                otp_2: '',
                otp_3: '',
                otp_4: '',
                otp_5: '',
                action: '',
                actions: '',
                isnotQRcode: true,
                isQRcode: false,
                isFailed: true,
                isSuccess: false,
                isActive: false,
                isActive2: false,
                isSearch: false,
                isHidden: true,
                isDisabled: 0,
                firstname: '',
                lastname: '',
                stud_no: '',
                result: '',
                error: '',
                image: '',
                mp3Source1: base_url + 'audio/access_granted.mp3',
                mp3Source2: base_url + 'audio/try_again.mp3',
                imgSource: base_url + 'images/1824.jpg',
                camera: 'auto',
                noRearCamera: false,
                noFrontCamera: false,
                switchcamera: base_url + 'images/switch-camera-icon.jpg'
            }
        },

        methods: {          
            
            showAlert() {
                // Use sweetalert2
                this.$swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a subject first!',
                    confirmButtonColor: '#f27474',
                });
            },

            showQR() {
                this.isnotQRcode = !this.isnotQRcode
                this.isQRcode = !this.isnotQRcode
            },

            showModal() {
                let self = this
                // if (self.subject == 'select a subject') {
                //     self.showAlert()
                // } else {
                    if (self.isQRcode == true) {
                        if (self.id_number !== '' && self.otp_1 !== '' && self.otp_2 !== '' && self.otp_3 !== '' && self.otp_4 !== '' && self.otp_5 !== '') { 
                            self.action = 'signin'
                            let otp = self.otp_1 + '' + self.otp_2 + '' + self.otp_3  + '' + self.otp_4  + '' + self.otp_5 
                            console.log(base_url + 'scan-otp?id='+ self.id_number +'&otp=' + otp + '&action=' + self.action + '&subject=' + self.subject)
                            axios.get(base_url + 'scan-otp?id='+ self.id_number +'&otp=' + otp + '&action=' + self.action + '&subject=' + self.subject)
                            .then(function (response) {
                                // handle success
                                var res = response  
                                console.log(response)
                                if (res.data.type == 'success') {
                                    self.resetModal()
                                    self.isActive = !self.isActive
                                    self.$refs['audio1'].play()
                                    self.$refs['my-modal'].hide()
                                    self.isFailed = false
                                    self.isSuccess = true
                                    res.data.data.forEach((info) => { 
                                        self.firstname = info.firstname
                                        self.lastname  = info.lastname
                                        self.stud_no   = info.stud_no
                                        if (info.stud_no) {
                                            var image = new Image()
                                            image.src = base_url + 'images/' + info.stud_no + '.jpg'
                                            if (image.width == 0) {
                                                self.imgSource = base_url + 'images/' + info.stud_no + '.JPG'
                                            } else {
                                                self.imgSource = base_url + 'images/' + info.stud_no + '.jpg'
                                            }
                                        }
                                    })
                                    self.actions = res.data.action
                                    self.isActive = !self.isActive
                                    //setTimeout(function(){ 
                                    //    location.replace("http://dioceseofantipolo.net/e/position/nsdaps/4");
                                    //}, 4000);
                                } else {
                                    self.$refs['audio2'].play()
                                }
                            })
                            .catch(function (error) {
                                // handle error
                                console.log(error);
                                self.$refs['audio2'].play()
                            })
                            .then(function () {
                                // always executed
                            });
                        }
                    } else {
                        self.$refs['my-modal'].show()
                        self.isActive = !self.isActive
                        self.action = 'signin'
                    }
                // }
            },

            showModal2() {
                let self = this
                if (self.subject == 'select a subject') {
                    self.showAlert()
                } else {
                    if (self.isQRcode == true) {
                        if (self.id_number !== '' && self.otp_1 !== '' && self.otp_2 !== '' && self.otp_3 !== '' && self.otp_4 !== '' && self.otp_5 !== '') { 
                            self.action = 'signout'
                            let otp = self.otp_1 + '' + self.otp_2 + '' + self.otp_3  + '' + self.otp_4  + '' + self.otp_5 
                            console.log(base_url + 'scan-otp?id='+ self.id_number +'&otp=' + otp + '&action=' + self.action + '&subject=' + self.subject)
                            axios.get(base_url + 'scan-otp?id='+ self.id_number +'&otp=' + otp + '&action=' + self.action + '&subject=' + self.subject)
                            .then(function (response) {
                                // handle success
                                var res = response  
                                console.log(response)
                                if (res.data.type == 'success') {
                                    self.resetModal()
                                    self.isActive = !self.isActive
                                    self.$refs['audio1'].play()
                                    self.$refs['my-modal'].hide()
                                    self.isFailed = false
                                    self.isSuccess = true
                                    res.data.data.forEach((info) => { 
                                        self.firstname = info.firstname
                                        self.lastname  = info.lastname
                                        self.stud_no   = info.stud_no
                                        if (info.stud_no) {
                                            var image = new Image()
                                            image.src = base_url + 'images/' + info.stud_no + '.jpg'
                                            if (image.width == 0) {
                                                self.imgSource = base_url + 'images/' + info.stud_no + '.JPG'
                                            } else {
                                                self.imgSource = base_url + 'images/' + info.stud_no + '.jpg'
                                            }
                                        }
                                    })
                                    self.actions = res.data.action
                                    self.isActive = !self.isActive
                                    //setTimeout(function(){ 
                                    //    location.replace("http://dioceseofantipolo.net/e/position/nsdaps/4");
                                    //}, 4000);
                                } else {
                                    self.$refs['audio2'].play()
                                }
                            })
                            .catch(function (error) {
                                // handle error
                                console.log(error);
                                self.$refs['audio2'].play()
                            })
                            .then(function () {
                                // always executed
                            });
                        }
                    } else {
                        self.$refs['my-modal'].show()
                        self.isActive2 = !self.isActive2
                        self.action = 'signout'
                    }
                }
            },

            resetModal() {
                this.isActive = false
                this.isActive2 = false
                this.isSearch = false
                this.isHidden = true
                this.action = ''
                this.mobile_number = ''
                this.id_number = ''
                this.otp_1 = ''
                this.otp_2 = ''
                this.otp_3 = ''
                this.otp_4 = ''
                this.otp_5 = ''
                this.isQRcode = false
                this.isnotQRcode = true
                this.subject = 'select a subject'
            },

            searchMethod() {
                let self = this
                if (this.id_number !== '') { 
                    console.log(base_url + 'search?id_number='+ self.id_number)
                    axios.get(base_url + 'search?id_number='+ self.id_number)
                    .then(function (response) {
                        // handle success
                        var res = response  
                        console.log(response)
                        if (res.data.search == true) {
                            self.isSearch = true
                            self.isHidden = true
                        } else {
                            self.isSearch = false
                            self.isHidden = false
                        }
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                        self.$refs['audio2'].play()
                    })
                    .then(function () {
                        // always executed
                    });
                }
            },

            requestOTP() {
                let self = this
                if (this.id_number !== '') { 
                    console.log(base_url + 'request-otp?id_number='+ self.id_number + '&mobile_number=' + self.mobile_number)
                    axios.get(base_url + 'request-otp?id_number='+ self.id_number + '&mobile_number=' + self.mobile_number)
                    .then(function (response) {
                        // handle success
                        var res = response  
                        console.log(response)
                        if (res.data.type == 'success') {
                            self.isDisabled = 1
                            self.isHidden = true
                        } else {
                            self.isDisabled = 0
                            self.$refs['audio2'].play()
                        }
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                        self.$refs['audio2'].play()
                    })
                    .then(function () {
                        // always executed
                    });
                }
            },

            onDecode (result) {
                let self = this
                this.result = result
                console.log(base_url + 'scan?id='+ result +'&action=' + self.action + '&subject=' + self.subject);
                axios.get(base_url + 'scan?id='+ result +'&action=' + self.action + '&subject=' + self.subject)
                .then(function (response) {
                    // handle success
                    var res = response  
                    console.log(response)
                    if (res.data.type == 'success') {
                        self.$refs['audio1'].play()
                        self.$refs['my-modal'].hide()
                        self.isFailed = false
                        self.isSuccess = true
                        res.data.data.forEach((info) => { 
                            self.firstname = info.firstname
                            self.lastname  = info.lastname
                            self.stud_no   = info.stud_no
                            if (info.stud_no) {
                                var image = new Image()
                                image.src = base_url + 'images/' + info.stud_no + '.jpg'
                                if (image.width == 0) {
                                    self.imgSource = base_url + 'images/' + info.stud_no + '.JPG'
                                } else {
                                    self.imgSource = base_url + 'images/' + info.stud_no + '.jpg'
                                }
                            }
                        })
                        self.actions = res.data.action
                        //setTimeout(function(){ 
                        //    location.replace("http://dioceseofantipolo.net/e/position/nsdaps/4");
                        //}, 4000);
                    } else {
                        self.$refs['audio2'].play()
                    }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                    self.$refs['audio2'].play()
                    self.isFailed = true
                    self.isSuccess = false
                    self.resetModal()
                })
                .then(function () {
                    // always executed
                });
            },

            switchCamera () {
                switch (this.camera) {
                    case 'front':
                        this.camera = 'auto'
                        break
                    case 'auto':
                        this.camera = 'front'
                        break
                }
            },

            async onInit (promise) {
                try {
                    await promise
                } catch (error) {
                    const triedFrontCamera = this.camera === 'front'
                    const triedRearCamera = this.camera === 'rear'

                    const cameraMissingError = error.name === 'OverconstrainedError'

                    if (triedRearCamera && cameraMissingError) {
                        this.noRearCamera = true
                    }

                    if (triedFrontCamera && cameraMissingError) {
                        this.noFrontCamera = true
                    }

                    console.error(error)
                }
            }
        },

        mounted(){
            let self = this
            console.log(base_url + 'fetch-subjects')
            axios.get(base_url + 'fetch-subjects')
            .then(function (response) {
                // handle success
                var res = response  
                console.log(response)
                console.log(self.options = res.data)
            })
            .catch(function (error) {
                // handle error
                console.log(error);
                self.$refs['audio2'].play()
            })
            .then(function () {
                // always executed
            });
        }
    }
</script>

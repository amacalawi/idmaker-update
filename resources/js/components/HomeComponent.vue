<template>
    <section class="main">
        <div class="row">
            <div class="layer offset-md-3 col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="m-form__group form-group required text-center">
                            <div class="m-radio-inline">
                                <label class="m-radio m-radio--solid">
                                    <input v-model="type" name="type" type="radio" value="printed" checked="checked">
                                    Printed
                                    <span></span>
                                </label>
                                <label class="m-radio m-radio--solid">
                                    <input v-model="type" name="type" type="radio" value="delivered">
                                    Delivered
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <qrcode-stream :camera="camera" @decode="onDecode" @init="onInit" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div v-bind:class="{hidden: isFailed}" class="alert alert-danger alert-dismissible fade show m-alert m-alert--air m-alert--outline m-alert--outline-2x primary-bg primary-border" role="alert">
                            <i class="fa fa-check"></i>
                            <strong>
                                Well done! The information has been successfully updated.
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row hidden">
            <audio ref="audio1" :src="mp3Source1" preload="auto"></audio>
            <audio ref="audio2" :src="mp3Source2" preload="auto"></audio>
        </div>
    </section>
</template>



<script>
    import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'

    const axios = require('axios');
    
    export default {

        components: { QrcodeStream, QrcodeCapture },

        data () {

            return {
                camera: 'auto',
                noRearCamera: false,
                noFrontCamera: false,
                switchcamera: base_url + 'images/switch-camera-icon.jpg',
                onTrack: this.paintOutline,
                isFailed: true,
                isSuccess: false,
                result: null,
                type: 'printed',
                mp3Source1: base_url + 'audio/access_granted.mp3',
                mp3Source2: base_url + 'audio/try_again.mp3',
            }
        },

        methods: {     

            refreshData() {
                this.isFailed = true;
                this.isSuccess = false;
                this.result = null;
            },

            paintOutline (detectedCodes, ctx) {
                for(const i = 0; i < detectedCodes.length; i++) {
                    const { boundingBox: { x, y, width, height } } = detectedCodes[i]

                    ctx.lineWidth = 2
                    ctx.strokeStyle = '#007bff'
                    ctx.strokeRect(x, y, width, height)
                }
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
                } catch (e) {
                    console.error(e)
                } finally {
                    this.showScanConfirmation = this.camera === "off"
                }
            },

            async onDecode (content) {
                let self = this
                self.result = content
                console.log(base_url + 'scan?type=' + self.type + '&id=' + self.result);
                axios.get(base_url + 'scan?type=' + self.type + '&id= '+ self.result)
                .then(function (response) {
                    // handle success
                    var res = response  
                    console.log(response)
                    if (res.data.type == 'success') {
                        self.$refs['audio1'].play()
                        self.isFailed = false
                        self.isSuccess = true
                        setTimeout(function(){ 
                           self.refreshData();
                        }, 4000);
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

                
                this.pause()
                await this.timeout(500)
                this.unpause()
            },

            unpause () {
                this.camera = 'auto'
            },

            pause () {
                this.camera = 'off'
            },

            timeout (ms) {
                return new Promise(resolve => {
                    window.setTimeout(resolve, ms)
                })
            }

        },

        mounted(){
            let self = this
            // console.log(base_url + 'fetch-subjects')
            // axios.get(base_url + 'fetch-subjects')
            // .then(function (response) {
            //     // handle success
            //     var res = response  
            //     console.log(response)
            //     console.log(self.options = res.data)
            // })
            // .catch(function (error) {
            //     // handle error
            //     console.log(error);
            //     self.$refs['audio2'].play()
            // })
            // .then(function () {
            //     // always executed
            // });
        }
    }
</script>

new Vue({
    el: '#app',
    data: {
        message: 'Sala de espera',
        fila1:[],
    },
    mounted:function (){
        let context = this;
        this.$nextTick(function () {
            // Code that will run only after the
            // entire view has been rendered
            setInterval(function () {
                context.getData()
            }, (5000));
            context.getData();
        })
    },
    methods:{
        cambiarEstado(asiento,index){
            asiento.estado = !asiento.estado;
            if(index>1){
                if(asiento.estado){
                    asiento.class = "bg-primary"
                    if(index%2==0){
                        asiento.class = "text-error b-red"
                        asiento.nombre = "COVID PROTOCOL"
                    }
                }else{
                    asiento.class = "b-primary"
                }
            }else{
                asiento.class = "text-error b-red"
                asiento.nombre = "COVID PROTOCOL"
            }

        },

        getData(){
            let context = this;
            // Make a request for a user with a given ID
            //setInterval()
            axios.get('/lolitha/api/sensores/')
                .then(function (response) {
                    // handle success
                    context.fila1 =  response.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
        },


    }
})
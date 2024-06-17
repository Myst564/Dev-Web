<template>
    <div class="m-20">
        <h2 class="text-4xl text-zinc-900 flex justify-center p-2">Les  précautions face au soleil</h2>
        <span  class="text-3xl justify-center w-full flex p-2">Quelques bons reflexes à avoir</span>
        <div  class=" w-full flex justify-center  border">
           <!-- Actual Caroussel-->
            <div  class="h-96  w-2/3 flex place-content-evenly   items-center">
                
                <div class=" bg-slate-500 rounded-full w-15 flex items-center h-10">
               
                <span @click="prevContent" class="text-white  cursor-pointer text-5xl duration-500 hover:text-sky-200 ">&#10096;</span> </div>
                <span  class="w-2/3 text-5xl flex flex-row justify-center duration-300  cursor-pointer" > <img  :src="content[ this.index]" alt="image" class="h-96  " @click="nextContent">  </span>

               <div class=" bg-slate-500 rounded-full w-15 flex items-center h-10 "> <span @click="nextContent" class=" text-white cursor-pointer text-5xl duration-500 hover:text-sky-200 ">&#10097;</span></div>
            </div>

        </div>
    </div>
</template>
<script>
    export default{  
        name:'precautions',
        data(){
            return{
                index:0,
                content:["img/2.avif","img/3.avif","img/2.avif","img/4.avif","img/ss.jpg","img/chapeaux.jpg"]
            }
        },
        methods:{
            nextContent(){
            
                if(this.index < this.content.length -1 ){
                    this.index++;
                }else{
                    this.index = 0
                }
            },
            prevContent(){
                if(this.index > 0 ) {
                    this.index--;
            }else{
                this.index = this.content.length -1;
            }
        }
    }
}
</script>
<template>
    <div class=" w-full">
        
        <h3 class="text-3xl p-8">{{ title }}</h3>
        <div class="w-full  flex flex-row justify-center gap-3 p-8">
            
            <button class="p-8 bg-orange-400 text-2xl h-7 w-56  text-white flex flext-row items-center justify-center rounded-md cursor-pointer hover:bg-orange-300 duration-500 " @click="Showall">#All</button>


            <button @click="Chapeau" class="p-8 bg-orange-400 text-2xl h-7 w-56  text-white flex flext-row items-center justify-center rounded-md cursor-pointer hover:bg-orange-300 duration-500">#chapeau</button>

            <button @click="StevensonShow" class="p-8 bg-orange-400 text-2xl h-7 w-56  text-white flex flext-row items-center justify-center rounded-md cursor-pointer hover:bg-orange-300 duration-500">#Stevenson</button>

            <button @click="Affiches" class="p-8 bg-orange-400 text-2xl h-7 w-56  text-white flex flext-row items-center justify-center rounded-md cursor-pointer hover:bg-orange-300 duration-500">#affiche</button>

            <button @click="Communication" class="p-8 bg-orange-400 text-2xl h-7 w-56  text-white flex flext-row items-center justify-center rounded-md cursor-pointer hover:bg-orange-300 duration-500">#communication</button>
            
            <button @click="Journal" class="p-8 bg-orange-400 text-2xl h-7 w-56  text-white flex flext-row items-center justify-center rounded-md cursor-pointer hover:bg-orange-300 duration-500">#Journal</button>

        </div>
        <div class="flex flex-wrap  galerie p-8"> 
            
            <div class="flex flex-row h-96r" v-if="whatToShow === 'all'">
                <div class="h-56 flex flex-row flex-wrap w-full " v-for="obj in pics" >
                    <img class="w-50 h-52  p-2 cursor-pointer hover:scale-105 duration-500" :src="obj.link" :alt="obj.alt" />
                </div>
            </div>
            <!-- Affiches -->
            <div v-else-if="whatToShow ==='affiche'">
            
                <div class="h-56 flex flex-row flex-wrap w-full " v-for="obj in pics" >
                    <span v-if="obj.tagg=='affiche'" >
                    <img class="w-50 h-52  p-2 cursor-pointer hover:scale-105 duration-500" :src="obj.link" :alt="obj.alt" /></span>
                </div>
            <!-- Journal -->
            </div>
            <div v-else-if="whatToShow ==='journal'">
             
                <div class="h-56 flex flex-row flex-wrap w-full " v-for="obj in pics" >
                    <span v-if="obj.tagg=='journal'" >
                    <img class="w-50 h-52  p-2 cursor-pointer hover:scale-105 duration-500" :src="obj.link" :alt="obj.alt" /></span>
                </div>

            </div>
             <!-- chapeaux -->
            <div v-else-if="whatToShow ==='chapeaux'">
                
                <div class="h-56 flex flex-row flex-wrap w-full " v-for="obj in pics" >
                    <span v-if="obj.tagg=='chapeaux'" >
                    <img class="w-50 h-52  p-2 cursor-pointer hover:scale-105 duration-500" :src="obj.link" :alt="obj.alt" /></span>
                </div>

            </div>
             <!-- Communication -->
            <div v-else-if="whatToShow ==='communication'">
           
                <div class="h-56 flex flex-row flex-wrap w-full " v-for="obj in pics" >
                    <span v-if="obj.tagg=='communication'" >
                    <img class="w-50 h-52  p-2 cursor-pointer hover:scale-105 duration-500" :src="obj.link" :alt="obj.alt" /></span>
                </div>

            </div>
            <!--Stevenson-->
            <div v-else-if="whatToShow ==='stevenson'">
             
                <div class="h-56 flex flex-row flex-wrap w-full " v-for="obj in pics" >
                    <span v-if="obj.tagg=='stevenson'" >
                    <img class="w-50 h-52  p-2 cursor-pointer hover:scale-105 duration-500" :src="obj.link" :alt="obj.alt" /></span>
                </div>

            </div>
            <!-- Error-->
            <div v-else ="whatToShow==='??'">
                <p class="text-red-600 " >there must be a mistake in the code</p>
                
                </div>

            </div>                    
            
        
        </div>
        <div>    

        </div>
    
</template>

<script>
    export default{
       
        data(){
            return{
                name:'galerie',
                whatToShow:'all',
                title:'Galerie Photo',
                pics:{
                    pic1:{
                        link:'/img/affiche.jpg',
                        tagg:'affiche',
                        alt:'affiche'
                    },
                    pic2:{
                        link:"/img/journal.jpg",
                        tagg:'journal',
                        alt:'Journale image'
                    },
                    pic3:{
                        link:'img/campagne1.jpg',
                        tagg:'communication',
                        alt:'communicaton'
                    },
                    pic4:{
                        link:'img/chapeaux.jpg',
                        tagg:'chapeaux',
                        alt:'chapeaux'
                    },
                    pic5:{
                        link:'img/ss.jpg',
                        tagg:'stevenson',
                        alt:'Ambasadeur'
                    },
                },
        
                    
                }
            },
            methods:{

                // changer la valeur de la vartiable whattoShow pour filtrer les images de la galerie
                Showall(){
                    this.whatToShow = 'all';
                    console.log(this.whatToShow);
                },
                StevensonShow(){
                    this.whatToShow = 'stevenson';
                    console.log(this.whatToShow);
                },
                Chapeau(){
                    this.whatToShow = 'chapeaux';
                    console.log(this.whatToShow);
                },
                Affiches(){
                    this.whatToShow = 'affiche';
                    console.log(this.whatToShow);
                },
                Communication(){
                    this.whatToShow = 'communication';
                    console.log(this.whatToShow);
                },
                Journal(){
                    this.whatToShow = 'journal';
                    console.log(this.whatToShow);
                },
                

        }   
        }
       
    

</script>
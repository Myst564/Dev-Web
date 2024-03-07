let moi = {
    prenom: "Miguel",
    nom: "Stc"
}


let animals = ["cat", "dog", "bat", moi]
console.log(animals[3].prenom)

for(let i=0;i<animals.length;i++) {
    console.log(animals[i])
}

animals.forEach(animal => {
    console.log(animal)
});

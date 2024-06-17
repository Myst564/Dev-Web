#class Modèle définissant la structure et comportement d'un objet 

class Person:
    def __init__(self, name, age):
        self.name = name
        self.age = age
    def greet(self):
        return f"Hello, my name is {self.name}"

#objet Instance de classe

person1 = Person("Alice", 30)
print(person1.greet())  # Output: Hello, my name is Alice

#Encupsulation : Concept de cacher des détails internet et afficher ceux nécessaire au bon fonctionnement des méthodes et attributs privés

class Person:
    def __init__(self, name, age):
        self.__name = name  # Attribut privé
        self.__age = age

    def get_name(self):
        return self.__name

    def set_name(self, name):
        self.__name = name
        
#Heritage: Création d'une nouvelle classe a partir d'une classe existante, qui a ses attributs et méthodes en héritage

class Employee(Person):
    def __init__(self, name, age, employee_id):
        super().__init__(name, age)
        self.employee_id = employee_id

    def work(self):
        return f"Employee {self.employee_id} is working"
    
    
#Polymorpisme:Objets de différentes classes traités comme instances d'une même classe parente, grâce aux méthodes communes

class Dog:
    def speak(self):
        return "Bark"

class Cat:
    def speak(self):
        return "Meow"

def make_animal_speak(animal):
    print(animal.speak())

make_animal_speak(Dog())  # Output: Bark
make_animal_speak(Cat())  # Output: Meow


#Abstraction : Concept de cacher les détails complexes et de montrer uniquement les fonctionnalités essentielles. Utilisés pour méthodes abstraites

from abc import ABC, abstractmethod

class Animal(ABC):
    @abstractmethod
    def speak(self):
        pass

class Dog(Animal):
    def speak(self):
        return "Bark"

class Cat(Animal):
    def speak(self):
        return "Meow"





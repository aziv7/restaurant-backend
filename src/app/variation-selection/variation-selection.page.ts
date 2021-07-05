import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { PlatService } from '../services/plat.service';

@Component({
  selector: 'app-variation-selection',
  templateUrl: './variation-selection.page.html',
  styleUrls: ['./variation-selection.page.scss'],
})
export class VariationSelectionPage implements OnInit {
private plat 
private prix:number
  constructor(private modalController: ModalController,private servicePlat:PlatService) { }

  ngOnInit() {
    this.plat=this.servicePlat.plat
    this.prix=Number(this.plat.prix)
  this.plat.modificateurs.map((m,i)=>{m.ingredients.map((ing,i1)=>{this.plat.modificateurs[i].ingredients[i1].checked=false})})

  console.log(this.plat)
  }
  addIngredient(event,ingredient,price:number){
    if(event.target.checked){
this.prix=this.prix+Number(price)
    }
    else{
      this.prix=this.prix-Number(price)
    }
    
  }
 dismiss(){
   this.modalController.dismiss();
 }
}

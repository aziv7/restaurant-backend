import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { PlatService } from '../services/plat.service';
import { VariationSelectionPage } from '../variation-selection/variation-selection.page';   
import { ModalController } from '@ionic/angular';
import {Subscription} from 'rxjs';
import { CategorieService } from '../services/categorie.service';
@Component({
  selector: 'app-home',
  templateUrl: './home.page.html',
  styleUrls: ['./home.page.scss'],
})
export class HomePage implements OnInit {
location: string = "home"; 
private sub1:Subscription
private sub:Subscription
  constructor(private route: Router,private servicePlat:PlatService,private categorieService:CategorieService,private modalController: ModalController) { }
private plats=[]
private categories=[]
  ngOnInit() {
    this.plats=this.servicePlat.getPlats()
    this.servicePlat.fetchPlats()
   this.sub= this.servicePlat.updatedPlats.subscribe(data=>this.plats=data)
   this.categories=this.categorieService.getCategories()
   this.sub1=this. categorieService.updatedCategories.subscribe(data=>this.categories=data)
  }

  variation_selection(){
    this.modalController.create({component:VariationSelectionPage}).then((modalElement)=>
    {
      modalElement.present();
    }
    )
  } 

offers() {
    this.route.navigate(['./offers']);
  } 
stores() {
    this.route.navigate(['./stores']);
  }    
items(i:number) { 
  this.categorieService.setSelected(i)
    this.route.navigate(['./items']);
  }
  ngOnDestroy() {
    this.sub.unsubscribe();
    this.sub1.unsubscribe();
}

}

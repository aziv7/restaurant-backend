import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { ModalController } from '@ionic/angular';
import { VariationSelectionPage } from '../variation-selection/variation-selection.page';   
import { IonSlides } from '@ionic/angular';
import { CategorieService } from '../services/categorie.service';
import { Categorie } from 'src/models/categorie.model';
import {Subscription} from 'rxjs';
@Component({ 
  selector: 'app-items',
  templateUrl: './items.page.html',
  styleUrls: ['./items.page.scss'],
})
export class ItemsPage implements OnInit {
 segment = 0;   
 private sub:Subscription
 @ViewChild('slides', { static: true }) slider: IonSlides;   
 categories:Categorie[]=[];  
 FavoriteIcon = false;    
  constructor(private route: Router,private modalController: ModalController,private serviceCategorie:CategorieService) { }

  ngOnInit() {
    this.segment=this.serviceCategorie.selected
    this.segmentChanged()
    this.categories=this.serviceCategorie.getCategories()
  this.sub=  this.serviceCategorie.updatedCategories.subscribe((data:Categorie[])=>this.categories=data)
  }
  
  async segmentChanged() {
    await this.slider.slideTo(this.segment);
  }

  async slideChanged() {
    this.segment = await this.slider.getActiveIndex();
  }  
    
  cart() {
    this.route.navigate(['./cart']);
  } 
  ngOnDestroy() {
    this.sub.unsubscribe();
    
}
 toggleFavoriteIcon(){
   this.FavoriteIcon = !this.FavoriteIcon;
   }
    
 reviews() {
    this.route.navigate(['./reviews']);
  }
    
  variation_selection(){
    this.modalController.create({component:VariationSelectionPage}).then((modalElement)=>
    {
      modalElement.present();
    }
    )
  } 
  
}

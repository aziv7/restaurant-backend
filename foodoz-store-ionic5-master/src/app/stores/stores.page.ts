import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { PlatService } from '../services/plat.service';
import {Subscription} from 'rxjs';
import { ModalController } from '@ionic/angular';
import { VariationSelectionPage } from '../variation-selection/variation-selection.page';   

@Component({
  selector: 'app-stores',
  templateUrl: './stores.page.html',
  styleUrls: ['./stores.page.scss'],
})
export class StoresPage implements OnInit {
private plats:Object[]=[]
private sub:Subscription
  constructor(private route: Router,private servicePlat:PlatService,private modalController: ModalController) { }

  ngOnInit() {
   this.plats= this.servicePlat.getPlats()
  
   this.sub= this.servicePlat.updatedPlats.subscribe(data=>this.plats=data)
  }
  variation_selection(){
    this.modalController.create({component:VariationSelectionPage}).then((modalElement)=>
    {
      modalElement.present();
    }
    )
  } 
  ngOnDestroy() {
    this.sub.unsubscribe();
    
}
}

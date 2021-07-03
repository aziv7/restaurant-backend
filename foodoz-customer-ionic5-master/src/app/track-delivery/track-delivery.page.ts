import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { ChatDeliveryPartnerPage } from '../chat-delivery-partner/chat-delivery-partner.page'; 
@Component({
  selector: 'app-track-delivery',
  templateUrl: './track-delivery.page.html',
  styleUrls: ['./track-delivery.page.scss'],
})
export class TrackDeliveryPage implements OnInit {

  constructor(private modalController: ModalController) { }

  ngOnInit() {
  }
 dismiss(){
   this.modalController.dismiss();
 }

chat_delivery_partner(){     
    this.modalController.create({component:ChatDeliveryPartnerPage}).then((modalElement)=>
    {
      modalElement.present();
    }
    )
  }     
}

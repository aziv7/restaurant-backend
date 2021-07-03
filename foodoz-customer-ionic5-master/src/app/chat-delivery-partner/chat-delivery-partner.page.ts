import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';

@Component({
  selector: 'app-chat-delivery-partner',
  templateUrl: './chat-delivery-partner.page.html',
  styleUrls: ['./chat-delivery-partner.page.scss'],
})
export class ChatDeliveryPartnerPage implements OnInit {

  constructor(private modalController: ModalController) { }

  ngOnInit() {
  }

 dismiss(){
   this.modalController.dismiss();
 }
}

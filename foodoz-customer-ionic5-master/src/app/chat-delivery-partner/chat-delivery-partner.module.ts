import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';

import { IonicModule } from '@ionic/angular';

import { ChatDeliveryPartnerPageRoutingModule } from './chat-delivery-partner-routing.module';

import { ChatDeliveryPartnerPage } from './chat-delivery-partner.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    TranslateModule,     
    ChatDeliveryPartnerPageRoutingModule
  ],
  declarations: [ChatDeliveryPartnerPage]
})
export class ChatDeliveryPartnerPageModule {}

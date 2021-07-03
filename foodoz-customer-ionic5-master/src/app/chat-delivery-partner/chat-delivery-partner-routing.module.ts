import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ChatDeliveryPartnerPage } from './chat-delivery-partner.page';

const routes: Routes = [
  {
    path: '',
    component: ChatDeliveryPartnerPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ChatDeliveryPartnerPageRoutingModule {}

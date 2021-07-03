import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
 
import { IonicModule } from '@ionic/angular';

import { AddMoneyOptionPageRoutingModule } from './add-money-option-routing.module';

import { AddMoneyOptionPage } from './add-money-option.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    TranslateModule,    
    AddMoneyOptionPageRoutingModule
  ],
  declarations: [AddMoneyOptionPage]
})
export class AddMoneyOptionPageModule {}

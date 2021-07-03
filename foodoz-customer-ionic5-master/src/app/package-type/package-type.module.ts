import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
 
import { IonicModule } from '@ionic/angular';

import { PackageTypePageRoutingModule } from './package-type-routing.module';

import { PackageTypePage } from './package-type.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    TranslateModule,    
    PackageTypePageRoutingModule
  ],
  declarations: [PackageTypePage]
})
export class PackageTypePageModule {}

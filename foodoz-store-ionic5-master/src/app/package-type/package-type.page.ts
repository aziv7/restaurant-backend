import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';

@Component({
  selector: 'app-package-type',
  templateUrl: './package-type.page.html',
  styleUrls: ['./package-type.page.scss'],
})
export class PackageTypePage implements OnInit {

  constructor(private modalController: ModalController) { }

  ngOnInit() {
  }

 dismiss(){
   this.modalController.dismiss();
 }
}

import { Component, OnInit, Inject } from '@angular/core';
import { Router } from '@angular/router';
import { NavController } from '@ionic/angular';
import { ModalController } from '@ionic/angular';
import { BuyappalertPage } from '../buyappalert/buyappalert.page'; 
import { APP_CONFIG, AppConfig } from '../app.config';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import {UserService} from '../services/user.service';
import {CookieService} from 'ngx-cookie-service';

@Component({
  selector: 'app-my-account',
  templateUrl: './my-account.page.html',
  styleUrls: ['./my-account.page.scss'],
})
export class MyAccountPage implements OnInit {
    getCurrentUser:any;
  constructor(@Inject(APP_CONFIG) public config: AppConfig,private cookieService:CookieService, private route: Router, private navCtrl: NavController, private modalController: ModalController, private http: HttpClient,private Userservice:UserService) {

       }

  ngOnInit() {}

saved_addresses() {
    this.route.navigate(['./saved-addresses']);
  }
terms_conditions() {
    this.route.navigate(['./terms-conditions']);
  } 
support() {
    this.route.navigate(['./support']);
  }
wallet() {
    this.route.navigate(['./wallet']);
  }
favorites() {
    this.route.navigate(['./favorites']);
  }
//about_us() {
//    this.route.navigate(['./about-us']);
//  }
settings() {
    this.route.navigate(['./settings']);
  }
phone_number() {this.Userservice.logout().subscribe((data)=> {console.log(data);
    this.cookieService.deleteAll();
    this.route.navigate(['./phone-number']);},
    (e)=>this.route.navigate(['./phone-number']));
  }

buyappalert(){
   this.modalController.create({component:BuyappalertPage}).then((modalElement)=>
   {
     modalElement.present();
   }
   )
 }

}

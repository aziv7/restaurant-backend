import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {UserService} from '../services/user.service';
import {User} from '../Models/User';
import {CookieService} from 'ngx-cookie-service';
@Component({
  selector: 'app-phone-number',
  templateUrl: './phone-number.page.html',
  styleUrls: ['./phone-number.page.scss'],
})
export class PhoneNumberPage implements OnInit {
    user: User;private CurrentUser:string;
  constructor(private route: Router,private  userservice:UserService,private cookieService:CookieService) {
      this.user= new User();
  }
loginError:any='';
  ngOnInit() {
  }
register() {
    this.route.navigate(['./register']);
  } 
 socila_login() {
    this.route.navigate(['./socila-login']);
  }
    login() {
this.userservice.Login(this.user).subscribe((data:object)=>{
        this.cookieService.set('login',this.user.login);
        let x=JSON.stringify(data);
       // this.cookieService.set('userConnected',JSON.parse(x).body.id);
        this.userservice.userConnected=JSON.parse(x).body.user;
        console.log(this.userservice.userConnected)
        this.route.navigate(['./tabs']);
    },
    (error => this.loginError='please check your login and your password'))
    }

}

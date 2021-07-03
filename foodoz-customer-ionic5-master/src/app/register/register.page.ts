import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {User} from '../Models/User';
import {UserService} from '../services/user.service';
import {AlertController} from "@ionic/angular";
import {CookieService} from "ngx-cookie-service";

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage implements OnInit {
user:User;
  constructor(private route: Router, private userservice:UserService,    public alertController: AlertController,private cookieService:CookieService
  ) {
this.user=new User();

  }

  ngOnInit() {
      if(this.userservice.userConnected!=null)
      {
          this.user=this.userservice.userConnected;
          this.userservice.getuserBylogin(this.cookieService.get('login')).subscribe((data)=>
      {this.user.login=data.login;
      })
          this.userservice.ModifyUser(this.user.id,this.user).subscribe(
              (data)=>console.log(data)
          );


      }
  }

verification() {
    //this.route.navigate(['./verification']);
    console.log(this.user);
if(this.userservice.userConnected!=null)
{
    this.userservice.AddUser(this.user).subscribe(
        (data)=>{
            this.alertController.create({cssClass: 'alertLogCss',
                header: 'Success',
                buttons: [ 'OK']
            }).then(res => {

                res.present();

            });
            //this.route.navigate(['./tabs'])
        }
    );

}

    else{
   this.userservice.AddUser(this.user).subscribe(
        (data)=>{console.log(data);
            this.alertController.create({cssClass: 'alertLogCss',
                header: 'check your email',
                buttons: [ 'OK']
            }).then(res => {

                res.present();

            });
        }
    )}
  } 
}

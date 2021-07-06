import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {User} from '../Models/User';
import {UserService} from '../services/user.service';
import {AlertController} from "@ionic/angular";
import {CookieService} from "ngx-cookie-service";
import {CoordonneesAuthentification} from "../Models/CoordonneesAuthentification";

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage implements OnInit {
    user: User;

    constructor(private route: Router, private userservice: UserService, public alertController: AlertController, private cookieService: CookieService
    ) {
        this.user = new User();
        this.user.coordonnees_authentification = new CoordonneesAuthentification();

    }

    ngOnInit() {
        if (this.cookieService.check('login')) {
            console.log(this.userservice.userConnected)
            this.userservice.getuserByIdWithcoordonnes(this.cookieService.get('id')).subscribe((data) => {//console.log(data)
                //  console.log(data.coordonnees_authentification)
                // this.cookieService.set('userConnected',JSON.parse(x).body.id);
                this.user = data;
                this.user.coordonnees_authentification.password = '';
                console.log(this.user)
            })


        }
    }

    verification() {
        //this.route.navigate(['./verification']);
        console.log(this.user);
        if (this.cookieService.check('login')) {
            console.log(this.user.id);
            console.log(this.user),
                this.userservice.ModifyUser(this.user.id, this.user).subscribe(
                    (data) => console.log(data)
                );
        }


        if (this.cookieService.check('login')==false) {
            this.userservice.AddUser(this.user).subscribe(
                (data) => {
                    this.alertController.create({
                        cssClass: 'alertLogCss',
                        header: 'Success',
                        buttons: ['OK']
                    }).then(res => {

                        res.present();

                    });
                    //this.route.navigate(['./tabs'])
                }
            );

        }


    }
}

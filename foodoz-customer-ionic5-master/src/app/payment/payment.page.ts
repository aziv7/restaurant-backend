import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import {Stripe} from "@ionic-native/stripe/ngx";

@Component({
  selector: 'app-payment',
  templateUrl: './payment.page.html',
  styleUrls: ['./payment.page.scss'],
})
export class PaymentPage implements OnInit {
paymentAmount:string='3.33';
currency:string='USD';
currencyIcon:string='$';
stripe_key='pk_test_51J9zB2EQevdhZyUKbD34af5o8NEQct3s0rITc98ouatqkHERg4set5NmzUJchhwB5SFxUjBAMuv0yh9SA0CSbgTz00Zsmv24b2';
cardDetails:any={};
  constructor(private navCtrl: NavController,private stripe:Stripe) { }

  ngOnInit() {
  }
  pay() {
    this.navCtrl.navigateRoot(['./order-placed']);
  }

    payStripe() {
        this.stripe.setPublishableKey(this.stripe_key);

        this.cardDetails = {
          number: '4242424242424242',
          expMonth: 12,
          expYear: 2025,
          cvc: '220'
        }

        this.stripe.createCardToken(this.cardDetails)
            .then(token => {
              console.log(token);
              this.makePayment(token.id);
            })
            .catch(error => console.error(error));
    }
  makePayment(token){

  }


}

import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { ChatDeliveryPartnerPage } from './chat-delivery-partner.page';

describe('ChatDeliveryPartnerPage', () => {
  let component: ChatDeliveryPartnerPage;
  let fixture: ComponentFixture<ChatDeliveryPartnerPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatDeliveryPartnerPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(ChatDeliveryPartnerPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

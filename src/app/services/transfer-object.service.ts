import { Subject } from 'rxjs';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class TransferObjectService {
  private subject = new Subject();
  constructor() { }

  sendObj(obj) {
    this.subject.next(obj);
  }

  accessObj() {
    return this.subject.asObservable();
  }
}

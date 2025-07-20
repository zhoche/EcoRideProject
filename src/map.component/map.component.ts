import { Component, AfterViewInit, Input, OnChanges } from '@angular/core';
import * as L from 'leaflet';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { environment } from '../environments/environment';

@Component({
  selector: 'app-map',
  template: `<div id="map" style="height: 600px;"></div>`,
  standalone: true,
  imports: [HttpClientModule],
})
export class MapComponent implements AfterViewInit, OnChanges {
  @Input() departure!: string;
  @Input() arrival!: string;

  private map?: L.Map;
  private routeLayer?: L.GeoJSON;

  constructor(private http: HttpClient) {}

  async ngAfterViewInit() {
    this.map = L.map('map').setView([43.6, 1.44], 9);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap'
    }).addTo(this.map);

    if (this.departure && this.arrival) {
      const coords = await this.fetchCoordinates(this.departure, this.arrival);
      if (coords) {
        this.drawRoute(this.map, coords.start, coords.end);
      }
    }
  }

  ngOnChanges() {
    if (this.map && this.departure && this.arrival) {
      this.fetchCoordinates(this.departure, this.arrival).then(coords => {
        if (coords) {
          this.drawRoute(this.map!, coords.start, coords.end);
        }
      });
    }
  }

  async fetchCoordinates(
    start: string,
    end: string
  ): Promise<{ start: number[]; end: number[] } | null> {
    try {
      const resStart: any = await this.http
        .get<{ lat: number; lon: number }>(
          `${environment.apiUrl}/api/geocode`,
          { params: { text: start } }
        )
        .toPromise();

      const resEnd: any = await this.http
        .get<{ lat: number; lon: number }>(
          `${environment.apiUrl}/api/geocode`,
          { params: { text: end } }
        )
        .toPromise();

      const startCoords = [resStart.lon, resStart.lat];
      const endCoords   = [resEnd.lon,   resEnd.lat];

      return { start: startCoords, end: endCoords };
    } catch (err) {
      console.error('Erreur géocodage', err);
      return null;
    }
  }

  async drawRoute(map: L.Map, start: number[], end: number[]) {
    try {
      // Appel au proxy Symfony
      const geojson: any = await this.http
        .post(`${environment.apiUrl}/api/directions`, { coordinates: [start, end] })
        .toPromise();

      // Supprime l’ancien tracé
      if (this.routeLayer) {
        map.removeLayer(this.routeLayer);
      }

      // Ajoute le nouveau tracé
      this.routeLayer = L.geoJSON(geojson, {
        style: { color: 'blue', weight: 4 }
      }).addTo(map);

      map.fitBounds(this.routeLayer.getBounds());
    } catch (err) {
      console.error('Erreur tracé itinéraire', err);
    }
  }
}

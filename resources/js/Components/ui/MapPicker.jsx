import { useEffect, useRef } from 'react';
import { MapPin, X } from 'lucide-react';

let L = null;

async function getLeaflet() {
    if (L) return L;
    L = await import('leaflet');
    await import('leaflet/dist/leaflet.css');
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });
    return L;
}

const BRAZIL_CENTER = [-14.235, -51.925];
const DEFAULT_ZOOM = 4;
const PIN_ZOOM = 13;

export default function MapPicker({ lat, lng, onChange, error, readOnly = false }) {
    const containerRef = useRef(null);
    const mapRef = useRef(null);
    const markerRef = useRef(null);

    const hasPin = lat != null && lng != null;

    useEffect(() => {
        let mounted = true;

        getLeaflet().then((Leaflet) => {
            if (!mounted || !containerRef.current || mapRef.current) return;

            const center = hasPin ? [lat, lng] : BRAZIL_CENTER;
            const zoom = hasPin ? PIN_ZOOM : DEFAULT_ZOOM;

            const map = Leaflet.map(containerRef.current, {
                zoomControl: true,
                dragging: !readOnly || hasPin,
                scrollWheelZoom: !readOnly,
                doubleClickZoom: !readOnly,
                boxZoom: !readOnly,
                keyboard: !readOnly,
            }).setView(center, zoom);
            mapRef.current = map;

            Leaflet.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19,
            }).addTo(map);

            if (hasPin) {
                markerRef.current = Leaflet.marker([lat, lng]).addTo(map);
            }

            if (!readOnly) {
                map.on('click', (e) => {
                    const { lat: clickLat, lng: clickLng } = e.latlng;
                    if (markerRef.current) {
                        markerRef.current.setLatLng([clickLat, clickLng]);
                    } else {
                        markerRef.current = Leaflet.marker([clickLat, clickLng]).addTo(map);
                    }
                    onChange(clickLat, clickLng);
                });
            }
        });

        return () => {
            mounted = false;
            if (mapRef.current) {
                mapRef.current.remove();
                mapRef.current = null;
                markerRef.current = null;
            }
        };
    }, []);

    useEffect(() => {
        if (!mapRef.current) return;
        if (!hasPin && markerRef.current) {
            markerRef.current.remove();
            markerRef.current = null;
        }
    }, [lat, lng]);

    if (readOnly && !hasPin) return null;

    return (
        <div className="flex flex-col gap-1.5">
            {!readOnly && (
                <div className="flex items-center justify-between">
                    <label className="text-sm font-semibold text-[#1C1917] flex items-center gap-1.5">
                        <MapPin size={14} className="text-[#2D6A4F]" />
                        Ponto de encontro — clique no mapa para marcar
                    </label>
                    {hasPin && (
                        <button
                            type="button"
                            onClick={() => onChange(null, null)}
                            className="text-xs text-[#78716C] hover:text-red-600 flex items-center gap-1 transition-colors"
                        >
                            <X size={12} /> Limpar pin
                        </button>
                    )}
                </div>
            )}

            <div
                ref={containerRef}
                className={`w-full rounded-xl border-2 overflow-hidden ${
                    readOnly ? 'h-48 border-[#E3CDA8]' : `h-56 ${error ? 'border-red-500' : 'border-[#E3CDA8]'}`
                }`}
            />

            {!readOnly && !hasPin && (
                <p className="text-xs text-[#A8A29E]">Nenhum ponto marcado ainda</p>
            )}
            {!readOnly && error && <p className="text-xs text-red-600">{error}</p>}
        </div>
    );
}
